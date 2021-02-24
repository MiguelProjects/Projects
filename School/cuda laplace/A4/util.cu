
/* ------------
 * This code is made by two university of toronto students, but was inspired by code that's property 
 * of the University of Toronto. 
 * We don't care abour copyright, however this makes our code look more professional :^)
 *
 * Authors: Miguel De Vera, Mark Sedhom
 * Inspiration: Bogdan Simion, Felipe de Azevedo Piovezan
 *
 * -------------
 */


#include "kernels.h"
#include "stdio.h"

#define MAX_BLOCK_SIZE 65535

void call_reduce(int numThreads, int numBlocks, int shMemSize, int32_t * d_output1, int32_t * d_output2, 
    int32_t * largest, int32_t * smallest, unsigned int n){
  switch (numThreads) {
    case 512:
    reduce_kernel<512><<<numBlocks, numThreads, shMemSize>>>(d_output1, d_output2, largest, smallest, n);
    break;
    case 256:
    reduce_kernel<256><<<numBlocks, numThreads, shMemSize>>>(d_output1, d_output2, largest, smallest, n);
    break;
    case 128:
    reduce_kernel<128><<<numBlocks, numThreads, shMemSize>>>(d_output1, d_output2, largest, smallest, n);
    break;
    case 64:
    reduce_kernel<64><<<numBlocks, numThreads, shMemSize>>>(d_output1, d_output2, largest, smallest, n);
    break;
    case 32:
    reduce_kernel<32><<<numBlocks, numThreads, shMemSize>>>(d_output1, d_output2, largest, smallest, n);
    break;
    case 16:
    reduce_kernel<16><<<numBlocks, numThreads, shMemSize>>>(d_output1, d_output2, largest, smallest, n);
    break;
    case 8:
    reduce_kernel<8><<<numBlocks, numThreads, shMemSize>>>(d_output1, d_output2, largest, smallest, n);
    break;
    case 4:
    reduce_kernel<4><<<numBlocks, numThreads, shMemSize>>>(d_output1, d_output2, largest, smallest, n);
    break;
    case 2:
    reduce_kernel<2><<<numBlocks, numThreads, shMemSize>>>(d_output1, d_output2, largest, smallest, n);
    break;
    case 1:
    reduce_kernel<1><<<numBlocks, numThreads, shMemSize>>>(d_output1, d_output2, largest, smallest, n);
    break;
    default:
    printf("invalid number of threads, exiting...\n");
    exit(1);
  }
}

int check_threads(int numThreads){
  if (numThreads > 512){
  return 512;
  }else if (numThreads > 256){
  return 256;
  }else if (numThreads > 128){
  return 128;
  }else if (numThreads > 64){
  return 64;
  }else if (numThreads > 32){
  return 32;
  }else if (numThreads > 16){
  return 16;
  }else if (numThreads > 8){
  return 8;
  }else if (numThreads > 4){
  return 4;
  }else if (numThreads > 2){
  return 2;
  }else{
  return 1;
  }
}


//supposed to be void - changed for debugging
__device__ void apply2d(const int8_t *filter, int32_t dimension,
  const int32_t *input, int32_t *output, int32_t width,
  int32_t height, int row, int col){
  // Variable that stores the offset of the pixel we're altering
  int current = row*width + col;    // {0, 1, 2, 3, 4, 5, 6, 7, 8} -> {0, 1, 2,
                                    //                                 3, 4, 5,    
                                    //                                 6, 7, 8}
                                    //
                                    // offset = (row * width_of_matrix) + col

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

  // Place pixel in output
  output[current] = new_pixel;
  // return new_pixel;
}
  
  
__device__ void normalize_one(int32_t *image, int32_t width, int32_t height,
  int32_t * smallest, int32_t * biggest, int row, int col){

  int32_t pixel_idx = row*width + col;

  if (smallest[0] == biggest[0]) {
    return;
  }

  image[pixel_idx] = ((image[pixel_idx] - smallest[0]) * 255) / (biggest[0] - smallest[0]);
}


/* Use this function to print the time of each of your kernels.
* The parameter names are intuitive, but don't hesitate to ask
* for clarifications.
* DO NOT modify this function.*/
void print_run(float time_cpu, int kernel, float time_gpu_computation,
  float time_gpu_transfer_in, float time_gpu_transfer_out) {
printf("%12.6f ", time_cpu);
printf("%5d ", kernel);
printf("%12.6f ", time_gpu_computation);
printf("%14.6f ", time_gpu_transfer_in);
printf("%15.6f ", time_gpu_transfer_out);
printf("%13.2f ", time_cpu / time_gpu_computation);
printf("%7.2f\n", time_cpu / (time_gpu_computation + time_gpu_transfer_in +
                  time_gpu_transfer_out));
}