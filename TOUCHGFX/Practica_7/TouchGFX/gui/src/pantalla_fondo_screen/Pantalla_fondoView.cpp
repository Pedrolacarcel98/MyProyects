#include <gui/pantalla_fondo_screen/Pantalla_fondoView.hpp>


#include "stm32f7xx_hal.h"

Pantalla_fondoView::Pantalla_fondoView()
{

}

void Pantalla_fondoView::setupScreen()
{
    Pantalla_fondoViewBase::setupScreen();
}

void Pantalla_fondoView::tearDownScreen()
{
    Pantalla_fondoViewBase::tearDownScreen();
}

void Pantalla_fondoView::funcion_led(){

	if(boton_led.getState()){
		HAL_GPIO_WritePin(GPIOK, GPIO_PIN_3, GPIO_PIN_SET);
	}
	else{
		HAL_GPIO_WritePin(GPIOK, GPIO_PIN_3, GPIO_PIN_RESET);

	}

}
