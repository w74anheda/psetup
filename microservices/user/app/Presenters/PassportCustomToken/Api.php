<?php

namespace App\Presenters\PassportCustomToken;

use App\Presenters\Presenter as ModelPresenter;
use Jenssegers\Agent\Agent;

class Api extends ModelPresenter
{

    private function user_agent_parser()
    {
        $agent = new Agent();
        $agent->setUserAgent($this->model->user_agent);
        return $agent;
    }

    public function browser()
    {
        return $this->user_agent_parser()->browser();
    }

    public function os()
    {
        $platform = $this->user_agent_parser()->platform();
        $version  = $this->user_agent_parser()->version($this->user_agent_parser()->platform());
        return $platform . ' ' . $version;
    }
}
