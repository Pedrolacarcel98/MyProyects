#include <gui/screen1_screen/Screen1View.hpp>

Screen1View::Screen1View()
{

}

void Screen1View::setupScreen()
{
    Screen1ViewBase::setupScreen();
}

void Screen1View::tearDownScreen()
{
    Screen1ViewBase::tearDownScreen();
}


void Screen1View::set_button(bool state)
{
    led_on.setVisible(state);
    led_on.invalidate();
}


void Screen1View::set_temp(bool state)
{
    gauge1.setValue(state);
    led_on.invalidate();
}
