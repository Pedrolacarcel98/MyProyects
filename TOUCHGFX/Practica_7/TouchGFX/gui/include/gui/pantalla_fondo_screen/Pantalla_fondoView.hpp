#ifndef PANTALLA_FONDOVIEW_HPP
#define PANTALLA_FONDOVIEW_HPP

#include <gui_generated/pantalla_fondo_screen/Pantalla_fondoViewBase.hpp>
#include <gui/pantalla_fondo_screen/Pantalla_fondoPresenter.hpp>

class Pantalla_fondoView : public Pantalla_fondoViewBase
{
public:
    Pantalla_fondoView();
    virtual ~Pantalla_fondoView() {}
    virtual void setupScreen();
    virtual void tearDownScreen();
    virtual void funcion_led();

protected:
};

#endif // PANTALLA_FONDOVIEW_HPP
