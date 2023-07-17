<?php
namespace App\Presenters;

use InvalidArgumentException;

trait PresentAble
{
    protected Presenter $presenterHandlerInstance;
    abstract protected function presenterHandler(): Presenter;

    public function setPresenter(string $presenterHandler)
    {
        $this->presenterHandlerInstance = new $presenterHandler($this);
    }

    public function present()
    {
        if(!isset($this->presenterHandlerInstance))
        {
            $this->presenterHandlerInstance = $this->presenterHandler();
        }

        return $this->presenterHandlerInstance;
    }

}
