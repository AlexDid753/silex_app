<?php
class User implements ControllerProviderInterface{
 
    public function connect(Application $app)
    {
        $users = $app["controllers_factory"];
 
        $users->get("/", "MyApp\\Controller\\UserController::index");
 
        $users->post("/", "MyApp\\Controller\\UserController::store");
 
        $users->get("/{id}", "MyApp\\Controller\\UserController::show");
 
        $users->get("/edit/{id}", "MyApp\\Controller\\UserController::edit");
 
        $users->put("/{id}", "MyApp\\Controller\\UserController::update");
 
        $users->delete("/{id}", "MyApp\\Controller\\UserController::destroy");

        $users->before(function(){
            // check for something here
        });
 
        return $users;
    }
 
}