/*
 * button_task.h
 *
 *  Created on: Dec 15, 2025
 *      Author: PC1
 */

#ifndef APPLICATION_USER_CORE_BUTTON_TASK_H_
#define APPLICATION_USER_CORE_BUTTON_TASK_H_

#include "stm32f7xx_hal.h"
#include "FreeRTOS.h"
#include "queue.h"
#include "task.h"

void create_button_task();

#endif /* APPLICATION_USER_CORE_BUTTON_TASK_H_ */
