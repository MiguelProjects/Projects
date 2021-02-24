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
#include <string>
#include <unistd.h>

#include "pgm.h"
#include "clock.h"
#include "kernels.h"


int main(int argc, char **argv) {
  int c;
  std::string input_filename, cpu_output_filename, base_gpu_output_filename;
  if (argc < 3) {
    printf("Wrong usage. Expected -i <input_file> -o <output_file>\n");
    return 0;
  }

  while ((c = getopt(argc, argv, "i:o:")) != -1) {
    switch (c) {
    case 'i':
      input_filename = std::string(optarg);
      break;
    case 'o':
      cpu_output_filename = std::string(optarg);
      base_gpu_output_filename = std::string(optarg);
      break;
    default:
      return 0;
    }
  }

  pgm_image source_img;
  init_pgm_image(&source_img);
  float cpu_time = 0.0;
  const int8_t FILTER[] = {
    0, 1, 1, 2, 2, 2,   1,   1,   0, 1, 2, 4, 5, 5,   5,   4,   2,
    1, 1, 4, 5, 3, 0,   3,   5,   4, 1, 2, 5, 3, -12, -24, -12, 3,
    5, 2, 2, 5, 0, -24, -40, -24, 0, 5, 2, 2, 5, 3,   -12, -24, -12,
    3, 5, 2, 1, 4, 5,   3,   0,   3, 5, 4, 1, 1, 2,   4,   5,   5,
    5, 4, 2, 1, 0, 1,   1,   2,   2, 2, 1, 1, 0,
  };

  const int FILTER_DIMENSION = 9;

  if (load_pgm_from_file(input_filename.c_str(), &source_img) != NO_ERR) {
    printf("Error loading source image.\n");
    return 0;
  }

  /* Do not modify this printf */
  printf("CPU_time(ms) Kernel GPU_time(ms) TransferIn(ms) TransferOut(ms) "
         "Speedup_noTrf Speedup\n");

  /* TODO: run your CPU implementation here and get its time. Don't include
   * file IO in your measurement.*/
  /* For example: */
  {
    std::string cpu_file = cpu_output_filename;
    pgm_image cpu_output_img;
    copy_pgm_image_size(&source_img, &cpu_output_img);
    // Start time
    // run_best_cpu(args...);  // From kernels.h
    // End time
    // print_run(args...)      // Defined on the top of this file

    Clock clock; 
    clock.start();
    run_best_cpu(FILTER, FILTER_DIMENSION, source_img.matrix, cpu_output_img.matrix, source_img.width,
      source_img.height);
    cpu_time = clock.stop();    

    save_pgm_to_file(cpu_file.c_str(), &cpu_output_img);
    destroy_pgm_image(&cpu_output_img);
  }

  /* TODO:
   * run each of your gpu implementations here,
   * get their time,
   * and save the output image to a file.
   * Don't forget to add the number of the kernel
   * as a prefix to the output filename:
   * Print the execution times by calling print_run().
   */

  /* For example: */
  {
    std::string gpu_file = "1" + base_gpu_output_filename;
    pgm_image gpu_output_img;
    copy_pgm_image_size(&source_img, &gpu_output_img);
    // Start time
    // run_kernel1(args...);  // From kernels.h
    // End time
    // print_run(args...)     // Defined on the top of this file
    Clock clock; 
    clock.start();
    run_kernel1(FILTER, FILTER_DIMENSION, source_img.matrix, gpu_output_img.matrix, source_img.width,
      source_img.height, cpu_time);

    save_pgm_to_file(gpu_file.c_str(), &gpu_output_img);
    destroy_pgm_image(&gpu_output_img);
  }

  {
    std::string gpu_file = "2" + base_gpu_output_filename;
    pgm_image gpu_output_img;
    copy_pgm_image_size(&source_img, &gpu_output_img);
    // Start time
    // run_kernel1(args...);  // From kernels.h
    // End time
    // print_run(args...)     // Defined on the top of this file

    run_kernel2(FILTER, FILTER_DIMENSION, source_img.matrix, gpu_output_img.matrix, source_img.width,
      source_img.height, cpu_time);

    save_pgm_to_file(gpu_file.c_str(), &gpu_output_img);
    destroy_pgm_image(&gpu_output_img);
  }

  {
    std::string gpu_file = "3" + base_gpu_output_filename;
    pgm_image gpu_output_img;
    copy_pgm_image_size(&source_img, &gpu_output_img);
    // Start time
    // run_kernel1(args...);  // From kernels.h
    // End time
    // print_run(args...)     // Defined on the top of this file
    Clock clock; 
    clock.start();
    run_kernel3(FILTER, FILTER_DIMENSION, source_img.matrix, gpu_output_img.matrix, source_img.width,
      source_img.height, cpu_time);

    save_pgm_to_file(gpu_file.c_str(), &gpu_output_img);
    destroy_pgm_image(&gpu_output_img);
  }

  {
    std::string gpu_file = "4" + base_gpu_output_filename;
    pgm_image gpu_output_img;
    copy_pgm_image_size(&source_img, &gpu_output_img);
    // Start time
    // run_kernel1(args...);  // From kernels.h
    // End time
    // print_run(args...)     // Defined on the top of this file
    Clock clock; 
    clock.start();
    run_kernel4(FILTER, FILTER_DIMENSION, source_img.matrix, gpu_output_img.matrix, source_img.width,
      source_img.height, cpu_time);

    save_pgm_to_file(gpu_file.c_str(), &gpu_output_img);
    destroy_pgm_image(&gpu_output_img);
  }

  {
    std::string gpu_file = "5" + base_gpu_output_filename;
    pgm_image gpu_output_img;
    copy_pgm_image_size(&source_img, &gpu_output_img);
    // Start time
    // run_kernel1(args...);  // From kernels.h
    // End time
    // print_run(args...)     // Defined on the top of this file

    run_kernel5(source_img.matrix, gpu_output_img.matrix, source_img.width,
      source_img.height, cpu_time);

    save_pgm_to_file(gpu_file.c_str(), &gpu_output_img);
    destroy_pgm_image(&gpu_output_img);
  }

  /* Repeat that for all 5 kernels. Don't hesitate to ask if you don't
   * understand the idea. */
}
