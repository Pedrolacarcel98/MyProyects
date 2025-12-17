#include <gui/screen1_screen/Screen1View.hpp>
#include <gui/screen1_screen/Screen1Presenter.hpp>

Screen1Presenter::Screen1Presenter(Screen1View& v)
    : view(v)
{

}

void Screen1Presenter::activate()
{

}

void Screen1Presenter::deactivate()
{

}


void Screen1Presenter::set_button(bool state)
{
	view.set_button(state);
}

void Screen1Presenter::set_temp(bool state)
{
	view.set_temp(state);
}


