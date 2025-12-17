/*
 * button_task.c
 *
 *  Created on: Dec 15, 2025
 *      Author: Pedro Lacárcel
 */




#include "button_task.h"

//extern ADC_HandleTypeDef hadc1;
QueueHandle_t button_queue;
TaskHandle_t button_task_handle;
QueueHandle_t temp_queue;
TaskHandle_t temp_task_handle;

extern
void buttonTask_Func(void *argument);
void tempTask_Func(void *argument);


void create_button_task(){


	button_queue = xQueueCreate(1, sizeof(uint8_t));
	xTaskCreate(buttonTask_Func, "ButtonTask", 128, NULL, 1, &button_task_handle);
}


void create_temp_task(){


	temp_queue = xQueueCreate(1, sizeof(uint8_t));
	xTaskCreate(tempTask_Func, "TempTask", 128, NULL, 1, &temp_task_handle);
}

void buttonTask_Func(void *argument){
	uint8_t button_state;
	for(;;){

		button_state = HAL_GPIO_ReadPin(GPIOI, GPIO_PIN_11);

		xQueueSendFromISR(button_queue, &button_state, pdFALSE);

		vTaskDelay(pdMS_TO_TICKS(50));
	}
}

void tempTask_Func(void *argument){
	uint8_t temp_state;
	for(;;){

		HAL_ADC_Start(&hadc1);
		uint16_t raw_value = HAL_ADC_GetValue(&hadc1);

		float vsense = (float)raw_value * (3.3f / 4095.0f);



		uint16_t temperatura = ((vsense - 0.76)/2.5) + 25.0;
		xQueueSendFromISR(temperatura, &temp_state, pdFALSE);

		vTaskDelay(pdMS_TO_TICKS(50));
	}


}




