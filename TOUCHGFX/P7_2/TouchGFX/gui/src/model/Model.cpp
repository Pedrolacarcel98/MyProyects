#include <gui/model/Model.hpp>
#include <gui/model/ModelListener.hpp>
#include "FreeRTOS.h"
#include "queue.h"

extern "C"{
extern QueueHandle_t button_queue;
extern QueueHandle_t temp_queue;

}

Model::Model() : modelListener(0)
{

}

void Model::tick()
{
	if(xQueueReceive(button_queue, &button_state, 0) ==  pdPASS){
		modelListener -> set_button(button_state);
	}
	if(xQueueReceive(temp_queue, &temp_state, 0) ==  pdPASS){
		modelListener -> set_temp(temp_state);
	}


}
