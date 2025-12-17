#ifndef MODELLISTENER_HPP
#define MODELLISTENER_HPP

#include <gui/model/Model.hpp>

class ModelListener
{
public:
    ModelListener() : model(0) {}
    
    virtual ~ModelListener() {}

    void bind(Model* m)
    {
        model = m;
    }

    virtual void set_button(bool state);
    virtual void set_temp(bool state);


protected:
    Model* model;
};

#endif // MODELLISTENER_HPP
