<?php

namespace Kanboard\Plugin\GitlabAuth;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Translator;
use Kanboard\Core\Security\Role;
use Kanboard\Plugin\GitlabAuth\Auth\GitlabAuthProvider;

class Plugin extends Base
{
    public function initialize()
    {
        $this->on('app.bootstrap', function ($container) {
            Translator::load($container['config']->getCurrentLanguage(), __DIR__.'/Locale');
        });

        $this->authenticationManager->register(new GitlabAuthProvider($this->container));
        $this->applicationAccessMap->add('OAuth', 'handler', Role::APP_PUBLIC);

        $this->route->addRoute('/oauth/gitlab', 'OAuth', 'handler', 'GitlabAuth');

        $this->template->hook->attach('template:auth:login-form:after', 'GitlabAuth:auth/login');
        $this->template->hook->attach('template:config:integrations', 'GitlabAuth:config/integration');
        $this->template->hook->attach('template:user:external', 'GitlabAuth:user/external');
        $this->template->hook->attach('template:user:authentication:form', 'GitlabAuth:user/authentication');
        $this->template->hook->attach('template:user:create-remote:form', 'GitlabAuth:user/create_remote');
    }

    public function getPluginName()
    {
        return 'Gitlab Authentication';
    }

    public function getPluginDescription()
    {
        return t('Use Gitlab as authentication provider');
    }

    public function getPluginAuthor()
    {
        return 'Frédéric Guillot';
    }

    public function getPluginVersion()
    {
        return '1.0.0';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/kanboard/plugin-gitlab-auth';
    }
}