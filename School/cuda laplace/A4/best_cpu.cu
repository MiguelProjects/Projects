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

#include "kernels.h"
#include <pthread.h>
#include <stdio.h>
#include <stdlib.h>
#include <limits.h>

pthread_mutex_t mutex;

typedef struct common_work_t
{
    const int8_t *f;
    const int32_t *original_image;
    int32_t *output_image;
    int32_t width;
    int32_t height;
    int32_t max_threads;
    int32_t smallest;
    int32_t largest;
    pthread_barrier_t * barrier;
} common_work;

typedef struct work_t
{
    common_work *common;
    int32_t id;
} work_t;



/* Normalizes a pixel given the smallest and largest integer values
 * in the image */
void normalize_pixelcpu(int32_t *target, int32_t pixel_idx, int32_t smallest,
    int32_t largest) {
    if (smallest == largest) {
        return;
    }

    target[pixel_idx] = ((target[pixel_idx] - smallest) * 255) / (largest - smallest);
}




int32_t apply2dcpu(const int8_t *f, const int32_t *original, int32_t *target,
                int32_t width, int32_t height, int row, int column) {

    // Variable that stores the offset of the pixel we're altering
    int current = row*width + column;     // {0, 1, 2, 3, 4, 5, 6, 7, 8} -> {0, 1, 2,
                                          //                                 3, 4, 5,    
                                          //                                 6, 7, 8}
                                          //
                                          // offset = (row * width_of_matrix) + column

    // Storing value of dimension in easy to reach variable 
    int32_t dim = 9;
    // Variable which will hold the new value for the pixel
    int32_t new_pixel = 0;

    // Variable that stores the offset of the top left corner that the filter matrix covers
    int corner = current - dim/2 - dim/2*width;
    // Variables that store the right and left boundary of the row -> will help when dealing with 'edge' cases hehe
    int start_left = current - dim/2*width - column;
    int start_right = current - dim/2*width + width-column-1;
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
                // Check that the offset is within 0 and len(original)
                if(curr >= 0 && curr < width*height){
                    new_pixel += original[curr]*f[dh*dim + dw];
                }
            }
        }
        // Updating the boundaries
        left = left + width;
        right = right + width;
    }

    // Place pixel in target
    target[current] = new_pixel;

    return new_pixel;
}



void *sharding_work(void *work) {
    /* Your algorithm is essentially:
     *  1- Apply the filter on the image
     *  2- Wait for all threads to do the same
     *  3- Calculate global smallest/largest elements on the resulting image
     *  4- Scale back the pixels of the image. For the non work queue
     *      implementations, each thread should scale the same pixels
     *      that it worked on step 1.
     */
  
    
    
    work_t * not_common = (work_t*)work;
  
    // Extracting non shared data
    common_work * common = not_common->common;
    int32_t id = not_common->id;
    
    // Extracting all shared data
    pthread_mutex_lock(&mutex);
    int32_t height = common->height;
    int32_t width = common->width;
    int32_t num_threads = common->max_threads;
    const int8_t * f = common->f;
    const int32_t * original = common->original_image;
    int32_t * target = common->output_image;
    pthread_barrier_t * barrier = common->barrier;
    int32_t smallest = common->smallest;
    int32_t largest = common->largest;
    pthread_mutex_unlock(&mutex);
  
    int start, end;
    
    int rows_amount; 
    // Calculating the number of rows we will apply the filter to
    if (height%num_threads == 0){
        rows_amount = height/num_threads;
    }else{
        rows_amount = (height + num_threads - 1)/num_threads;
    }
    // Calculating the offset of where this thread starts altering and ends
    start = rows_amount * id*width;
    end = start + rows_amount*width; 
    if (start > width*height){
        start = width*height;
    }
    if (end > width*height){
        end = width*height;
    }
    for(int offset = start; offset<end; offset++){
        // Modifying the pixel
        int32_t px = apply2dcpu(f, original, target, width, height, offset/width, offset%width);
        // Checking for local smallest
        if(smallest > px){
            smallest = px;
        }
        // Checking for local largest
        if (largest < px){
            largest = px;
        }
    }

    pthread_mutex_lock(&mutex);
    // Checking for global smallest
    if(common->smallest > smallest){
        common->smallest = smallest;
    }
    // Checking for global largest
    if (common->largest < largest){
        common->largest = largest;
    }

    pthread_mutex_unlock(&mutex);

    pthread_barrier_wait(barrier);
    
    pthread_mutex_lock(&mutex);
    // Making sure local largest and smallest is updated
    smallest = common->smallest;
    largest = common->largest;
    pthread_mutex_unlock(&mutex);
    
    // Normalizing all target pixels
    
    for(int offset = start; offset<end; offset++){
        // printf("output[%d]:%d,   smallest:%d, largest:%d\n", offset, target[offset], smallest, largest);
        normalize_pixelcpu(target, offset, smallest, largest);
    }
    
  
    
    
    pthread_exit(NULL);
    return NULL;
  
  
}






void run_best_cpu(const int8_t *filter, int32_t dimension, const int32_t *input,
                  int32_t *output, int32_t width, int32_t height) {
    
                    
    int32_t num_threads = height;
    pthread_t threads[num_threads];

    // The barrier and mutex that will help keep threads in sync
    pthread_barrier_t barrier;
    pthread_barrier_init(&barrier, NULL, num_threads);
    pthread_mutex_init(&mutex, NULL);
    
    common_work * common = (common_work*)malloc(sizeof(common_work));
    *common = {filter, input, output, width, height, height, INT_MAX, INT_MIN, &barrier};
    
    work_t * not_common = (work_t*)malloc(num_threads*sizeof(work_t)); 
    
    for(int t = 0; t<num_threads; t++){
        not_common[t] = {common, t};
        if (pthread_create(&threads[t], NULL, sharding_work, (void*)&not_common[t])){
            fprintf(stderr, "Error with creating a pthread");
            exit(1);
        }
    }
    
    // Waiting for all threads to finish
    for(int i = 0; i<num_threads; i++){
        pthread_join(threads[i], NULL);
    }
    
    free(common);
    free(not_common);


}
