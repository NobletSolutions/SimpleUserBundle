<?php


namespace NS\SimpleUserBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('ns_simple_user');
        $rootNode    = $treeBuilder->getRootNode();

        $rootNode->children()
                 ->arrayNode('templates')
                 ->addDefaultsIfNotSet()
                 ->children()
                 ->scalarNode('login')->defaultValue('@NSSimpleUser/Login/base_login.html.twig')->end()
                 ->scalarNode('forgot')->defaultValue('@NSSimpleUser/Login/base_forgot.html.twig')->end()
                 ->scalarNode('forgot_success')->defaultValue('@NSSimpleUser/Login/base_forgot_success.html.twig')->end()
                 ->scalarNode('change_password')->defaultValue('@NSSimpleUser/User/base_change_password.html.twig')->end()
                 ->scalarNode('reset_password')->defaultValue('@NSSimpleUser/User/base_reset_password.html.twig')->end()
                 ->scalarNode('email')->defaultValue('@NSSimpleUser/Email/base_email.html.twig')->end()
                 ->scalarNode('admin_list')->defaultValue('@NSSimpleUser/Admin/list.html.twig')->end()
                 ->scalarNode('admin_edit')->defaultValue('@NSSimpleUser/Admin/edit.html.twig')->end()
                 ->scalarNode('admin_create')->defaultValue('@NSSimpleUser/Admin/create.html.twig')->end()
                 ->scalarNode('admin_view')->defaultValue('@NSSimpleUser/Admin/view.html.twig')->end()
                 ->end()
                 ->end()
                 ->arrayNode('roles')
                 ->isRequired()
                 ->scalarPrototype()->end()
                 ->end();

        return $treeBuilder;
    }
}
