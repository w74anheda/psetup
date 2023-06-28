<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class CreatePresenter extends GeneratorCommand
{

    protected $name = 'make:presenter';


    protected $description = 'Create a new presenter class';

    protected $type = 'Presenter';

    protected function getStub()
    {
        return __DIR__ .'/../../../'. '/stubs/presenter.php.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Presenters';
    }
}
