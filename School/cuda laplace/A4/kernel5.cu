/* ------------
 * This code is provided solely for the personal and private use of
 * students taking the CSC367H5 course at the University of Toronto.
 * Copying for purposes other than this use is expressly prohibited.
 * All forms of distribution of this code, whether as given or with
 * any changes, are expressly prohibited.
 *
 * Authors: Bogdan Simion, Felipe de Azevedo Piovezan
 *
 * All of the files in this directory and all subdirectories are:
 * Copyright (c) 2019 Bogdan Simion
 * -------------
 */

 #include <stdio.h>

 #include "kernels.h"
 #include "clock.h" 


#define MY_MIN(x, y) ((x < y) ? x : y)
#define MAX_BLOCK_SIZE 65535




/*************** COMMON WORK ***********************/
/* Process a single pixel and returns the value of processed pixel
 * TODO: you don't have to implement/use this function, but this is a hint
 * on how to reuse your code.
 * */

void run_kernel5(const int32_t *input,
  int32_t *output, int32_t width, int32_t height, float cpu_time) {
  // Figure out how to split the work into threads and call the kernel below.
  /* Allocate memory for the input and result on the kernel */
  int32_t * d_input;
  int32_t * d_output;
  
  float gpu_time, transfer_in, transfer_out;

  cudaMalloc((void **)&d_input, width*height*sizeof(int32_t));
  cudaMalloc((void **)&d_output, width*height*sizeof(int32_t));

	Clock clock;
  clock.start();
	/* Copy input from host to device memory */
  cudaMemcpy(d_input, input, width*height*sizeof(int32_t), cudaMemcpyHostToDevice);
  transfer_in = clock.stop();
  

  clock.start();

  
  kernel5<<<height, width>>>(d_input, d_output, width, height);

  unsigned int n; 
  int numThreads, numBlocks, shMemSize, maxBlocks;
  n = width*height;
  maxBlocks = MY_MIN(MAX_BLOCK_SIZE, n);
  numThreads = (n < 1024*2) ? (n>>1) : 1024;
  // Make sure numThreads is a power of 2
  numThreads = check_threads(numThreads);
  numBlocks = MY_MIN(maxBlocks, (n+(numThreads*2-1))/(numThreads*2));
  shMemSize = (numThreads <= 32) ? 2*numThreads * sizeof(int32_t) : numThreads * sizeof(int32_t);

  int32_t * smallest;
  int32_t * largest;


  cudaMalloc((void **)&smallest, width*height*sizeof(int32_t));
  cudaMalloc((void **)&largest, width*height*sizeof(int32_t));

  
  call_reduce(numThreads, numBlocks, 8*shMemSize, d_output, d_output, largest, smallest, n);



  while(numBlocks > 1){
    n = (unsigned int)numBlocks;
    numThreads = (n < 1024*2) ? (n>>1) : 1024;
    // Make sure numThreads is a power of 2
    numThreads = check_threads(numThreads);
    numBlocks = MY_MIN(maxBlocks, (n+(numThreads*2-1))/(numThreads*2));
    shMemSize = (numThreads <= 32) ? 2*numThreads * sizeof(int32_t) : numThreads * sizeof(int32_t);
    call_reduce(numThreads, numBlocks, 8*shMemSize, largest, smallest, largest, smallest, n);
  }

  normalize5<<<height, width>>>(d_output, width, height, smallest, largest);

  gpu_time = clock.stop();


  clock.start();
  cudaMemcpy(output, d_output, width*height*sizeof(int32_t), cudaMemcpyDeviceToHost);
  transfer_out = clock.stop();

  print_run(cpu_time, 5, gpu_time, transfer_in, transfer_out);
  
  cudaFree(smallest);
  cudaFree(largest);
  cudaFree(d_input);
  cudaFree(d_output);


}



__global__ void kernel5(const int32_t * input, int32_t * output, int32_t width,
                        int32_t height) {
  //sajs

  const int8_t filter[] = {
    0, 1, 1, 2, 2, 2,   1,   1,   0, 1, 2, 4, 5, 5,   5,   4,   2,
    1, 1, 4, 5, 3, 0,   3,   5,   4, 1, 2, 5, 3, -12, -24, -12, 3,
    5, 2, 2, 5, 0, -24, -40, -24, 0, 5, 2, 2, 5, 3,   -12, -24, -12,
    3, 5, 2, 1, 4, 5,   3,   0,   3, 5, 4, 1, 1, 2,   4,   5,   5,
    5, 4, 2, 1, 0, 1,   1,   2,   2, 2, 1, 1, 0,
  };
  unsigned int row = blockIdx.x;
  unsigned int col = threadIdx.x;

  // Variable that stores the offset of the pixel we're altering
  int current = row*width + col;    // {0, 1, 2, 3, 4, 5, 6, 7, 8} -> {0, 1, 2,
                                    //                                 3, 4, 5,    
                                    //                                 6, 7, 8}
                                    //
                                    // offset = (row * width_of_matrix) + col

  current = (current + blockDim.x)%(width*height); // Striding
  


  // Storing value of dimension in easy to reach variable 
  int32_t dim = 9;
  // Variable which will hold the new value for the pixel
  int32_t new_pixel = 0;

  // Variable that stores the offset of the top left corner that the filter matrix covers
  int corner = current - dim/2 - dim/2*width;
  // Variables that store the right and left boundary of the row -> will help when dealing with 'edge' cases hehe
  int start_left = current - dim/2*width - col;
  int start_right = current - dim/2*width + width-col-1;
  int left = start_left;
  int right = start_right;

  // Stores the offset of the current pixel which we are considering
  int curr;
  // Calculating the new pixel value
  if (row < 9 || height - row < 9 || col < 9 || width - col < 9  ){
    for (int dh = 0; dh < dim; dh++){
      for(int dw = 0; dw < dim; dw++){
        curr = corner + dh*width + dw;
        // Check that the current offset is within the boundaries
        if(curr >= left && curr <= right){
          // Check that the offset is within 0 and len(input)
          if(curr >= 0 && curr < width*height){
            new_pixel += input[curr]*filter[dh*dim + dw];
          }
        }
      }
    // Updating the boundaries
    left = left + width;
    right = right + width;
    }
  }

  // Place pixel in output
  output[current] = new_pixel;
    
}

__global__ void normalize5(int32_t *image, int32_t width, int32_t height,
                           int32_t * smallest, int32_t * biggest) {
  unsigned int row = blockIdx.x;
  unsigned int col = threadIdx.x;

  int32_t pixel_idx = row*width + col;

  if (smallest[0] == biggest[0]) {
    return;
  }

  image[pixel_idx] = ((image[pixel_idx] - smallest[0]) * 255) / (biggest[0] - smallest[0]);
               
}

