<?php

namespace Botble\AgentBroker\Supports;

class AgentBrokerManager
{
    public function module(): string
    {
        return AGENT_BROKER_MODULE_SCREEN_NAME;
    }
}
