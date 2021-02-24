
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
#include "limits.h"

#define MY_MAX(x, y) ((x >= y) ? x : y)
#define MY_MIN(x, y) ((x < y) ? x : y)



template <unsigned int blockSize>
__global__ void reduce_kernel(int32_t *g_idata_large, int32_t *g_idata_small, int32_t * largest, int32_t * smallest, unsigned int n){

  extern __shared__ int sdata_large[], sdata_small[];

  unsigned int tid = threadIdx.x;

  // Global thread id
  unsigned int i = blockIdx.x * (blockSize * 2) + threadIdx.x;
  unsigned int gridSize = (blockSize * 2 * gridDim.x);
  
  int32_t fl, sl, fs, ss;
  unsigned int sep = 2*blockSize;

  sdata_large[tid] = INT_MIN;
  sdata_small[tid+sep] = INT_MAX;

  while(i < n){
    fl = g_idata_large[i];
    fs = g_idata_small[i];
    
    if(i + blockSize >= n){
      // # if __CUDA_ARCH__>=200
      //   printf("HERE!!! i:%d, blockSize:%d, n:%d, fs:%d, fl%d, small:%d, large:%d\n", i, blockSize, n, fs, fl, g_idata_small[n-1], g_idata_large[n-1]);
      //   //why does this not work at certain points? how come putting this in the loop makes it go infinitely and die?
      // #endif
      sl = g_idata_large[n-1];
      ss = g_idata_small[n-1];
    }else{
      sl = g_idata_large[i+blockSize];
      ss = g_idata_small[i+blockSize];

    }
    sdata_large[tid] = MY_MAX(fl,sl);
    sdata_small[tid+sep] = MY_MIN(fs,ss);
    // # if __CUDA_ARCH__>=200
    //   printf("REDUCTION: sdata_small[%d]:%d, sdata_large[%d]:%d, i:%d, gridSize:%d, n:%d, blockSize:%d, blockid:%d\n", tid+sep, sdata_small[tid+sep], tid, sdata_large[tid], i, gridSize, n, blockSize, blockIdx.x);
    //   //why does this not work at certain points? how come putting this in the loop makes it go infinitely and die?
    // #endif 
    i += gridSize;
  }
  __syncthreads();

  if (blockSize >= 512) { 
    if (tid < 256){ 
        fl = sdata_large[tid];
        sl = sdata_large[tid+256];
        fs = sdata_small[tid+sep];
        ss = sdata_small[tid+sep+256];

        if(tid + 256 >= n){
          sl = sdata_large[n-1];
          ss = sdata_small[n-1];
        }

        sdata_large[tid] = MY_MAX(fl,sl);
        sdata_small[tid+sep] = MY_MIN(fs,ss);
    } __syncthreads();
  }
  if (blockSize >= 256) { 
    if (tid < 128){ 
        fl = sdata_large[tid];
        sl = sdata_large[tid+128];
        fs = sdata_small[tid+sep];
        ss = sdata_small[tid+sep+128];

        if(tid + 128 >= n){
          sl = sdata_large[n-1];
          ss = sdata_small[n-1];
        }

        sdata_large[tid] = MY_MAX(fl,sl);
        sdata_small[tid+sep] = MY_MIN(fs,ss);
    } __syncthreads();
  }
  if (blockSize >= 128) { 
    if (tid < 64){ 
        fl = sdata_large[tid];
        sl = sdata_large[tid+64];
        fs = sdata_small[tid+sep];
        ss = sdata_small[tid+sep+64];

        if(tid + 64 >= n){
          sl = sdata_large[n-1];
          ss = sdata_small[n-1];
        }

        sdata_large[tid] = MY_MAX(fl,sl);
        sdata_small[tid+sep] = MY_MIN(fs,ss);
    } __syncthreads();
  }
  

  if (tid < 32) {
    volatile int32_t * smem_large = sdata_large;
    volatile int32_t * smem_small = sdata_small;
    if (blockSize >= 64){
        fl = smem_large[tid];
        sl = smem_large[tid+32];
        fs = smem_small[tid+sep];
        ss = smem_small[tid+sep+32];


        if(tid + 32 >= n){
          sl = smem_large[n-1];
          ss = smem_small[n-1];
        }

        smem_large[tid] = MY_MAX(fl,sl);
        smem_small[tid+sep] = MY_MIN(fs,ss);

     
    } 
    if (blockSize >= 32){
        fl = smem_large[tid];
        sl = smem_large[tid+16];
        fs = smem_small[tid+sep];
        ss = smem_small[tid+sep+16];


        if(tid + 16 >= n){
          sl = smem_large[n-1];
          ss = smem_small[n-1];
        }

        smem_large[tid] = MY_MAX(fl,sl);
        smem_small[tid+sep] = MY_MIN(fs,ss);

    } 
    if (blockSize >= 16){
        fl = smem_large[tid];
        sl = smem_large[tid+8];
        fs = smem_small[tid+sep];
        ss = smem_small[tid+sep+8];


        if(tid + 8 >= n){
          sl = smem_large[n-1];
          ss = smem_small[n-1];
        }

        smem_large[tid] = MY_MAX(fl,sl);
        smem_small[tid+sep] = MY_MIN(fs,ss);
    } 
    if (blockSize >=  8){
        fl = smem_large[tid];
        sl = smem_large[tid+4];
        fs = smem_small[tid+sep];
        ss = smem_small[tid+sep+4];


        if(tid + 4 >= n){
          sl = smem_large[n-1];
          ss = smem_small[n-1];
        }

        smem_large[tid] = MY_MAX(fl,sl);
        smem_small[tid+sep] = MY_MIN(fs,ss);
    } 
    if (blockSize >=  4){
        fl = smem_large[tid];
        sl = smem_large[tid+2];
        fs = smem_small[tid+sep];
        ss = smem_small[tid+sep+2];

        if(tid + 2 >= n){
          sl = smem_large[n-1];
          ss = smem_small[n-1];
        }

        smem_large[tid] = MY_MAX(fl,sl);
        smem_small[tid+sep] = MY_MIN(fs,ss);
    } 
    if (blockSize >=  2){
        fl = smem_large[tid];
        sl = smem_large[tid+1];
        fs = smem_small[tid+sep];
        ss = smem_small[tid+sep+1];

        if(tid + 1 >= n){
          sl = smem_large[n-1];
          ss = smem_small[n-1];
        }

        smem_large[tid] = MY_MAX(fl,sl);
        smem_small[tid+sep] = MY_MIN(fs,ss);
    }

  } 

  if (tid == 0) { 
    largest[blockIdx.x] = sdata_large[0];
    smallest[blockIdx.x] = sdata_small[sep]; 
  }
}
