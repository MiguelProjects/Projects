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

#ifndef __KERNELS__H
#define __KERNELS__H


/* TODO: you may want to change the signature of some or all of those functions,
 * depending on your strategy to compute min/max elements.
 * Be careful: "min" and "max" are names of CUDA library functions
 * unfortunately, so don't use those for variable names.*/

void run_best_cpu(const int8_t *filter, int32_t dimension, const int32_t *input,
                  int32_t *output, int32_t width, int32_t height);

void run_kernel1(const int8_t *filter, int32_t dimension, const int32_t *input,
                 int32_t *output, int32_t width, int32_t height, float cpu_time);
__global__ void kernel1(const int8_t *filter, int32_t dimension,
                        const int32_t *input, int32_t *output, int32_t width,
                        int32_t height);
__global__ void normalize1(int32_t *image, int32_t width, int32_t height,
                           int32_t * smallest, int32_t * biggest);

void run_kernel2(const int8_t *filter, int32_t dimension, const int32_t *input,
                 int32_t *output, int32_t width, int32_t height, float cpu_time);
__global__ void kernel2(const int8_t *filter, int32_t dimension,
                        const int32_t *input, int32_t *output, int32_t width,
                        int32_t height);
__global__ void normalize2(int32_t *image, int32_t width, int32_t height,
                           int32_t * smallest, int32_t * biggest);

void run_kernel3(const int8_t *filter, int32_t dimension, const int32_t *input,
                 int32_t *output, int32_t width, int32_t height, float cpu_time);
__global__ void kernel3(const int8_t *filter, int32_t dimension,
                        const int32_t *input, int32_t *output, int32_t width,
                        int32_t height);
__global__ void normalize3(int32_t *image, int32_t width, int32_t height,
                           int32_t * smallest, int32_t * biggest);

void run_kernel4(const int8_t *filter, int32_t dimension, const int32_t *input,
                 int32_t *output, int32_t width, int32_t height, float cpu_time);
__global__ void kernel4(const int8_t *filter, int32_t dimension,
                        const int32_t *input, int32_t *output, int32_t width,
                        int32_t height);
__global__ void normalize4(int32_t *image, int32_t width, int32_t height,
                           int32_t * smallest, int32_t * biggest);



void run_kernel5(const int32_t *input, int32_t *output, int32_t width, int32_t height, float cpu_time);
__global__ void kernel5(const int32_t *input, int32_t *output, int32_t width,
                        int32_t height);
__global__ void normalize5(int32_t *image, int32_t width, int32_t height,
                           int32_t * smallest, int32_t * biggest);



// Reduction kernel
// Based off of the 7th kernel in lab10
template <unsigned int blockSize>
__global__ void reduce_kernel(int32_t *g_idata_large, int32_t *g_idata_small, 
                       int32_t * largest, int32_t * smallest, unsigned int n);
#include "reduction_kernel.h"


// Helper functions called by every kernel
void call_reduce(int numThreads, int numBlocks, int shMemSize, int32_t * d_output1, int32_t * d_output2, 
    int32_t * largest, int32_t * smallest, unsigned int n);

int check_threads(int numThreads);

void print_run(float time_cpu, int kernel, float time_gpu_computation,
               float time_gpu_transfer_in, float time_gpu_transfer_out);

#endif
