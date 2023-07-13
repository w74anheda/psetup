<?php
namespace App\Presenters;

use InvalidArgumentException;

trait PresentAble
{

    public function setPresenter(string $presenterHandler)
    {
        $this->presenterHandler = $presenterHandler;
    }

    public function present()
    {

        if(
            !$this->presenterHandler ||
            !class_exists($this->presenterHandler)
        ) throw new InvalidArgumentException('Presenter Handler Not Found');


        if(!is_subclass_of($this->presenterHandler, Presenter::class))
            throw new InvalidArgumentException('Invalid Presenter Handler');

        $this->presenterHandler = new $this->presenterHandler($this);
        return $this->presenterHandler;
    }

}
