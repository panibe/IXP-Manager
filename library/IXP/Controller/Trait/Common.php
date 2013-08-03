<?php

/*
 * Copyright (C) 2009-2013 Internet Neutral Exchange Association Limited.
 * All Rights Reserved.
 *
 * This file is part of IXP Manager.
 *
 * IXP Manager is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation, version v2.0 of the License.
 *
 * IXP Manager is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for
 * more details.
 *
 * You should have received a copy of the GNU General Public License v2.0
 * along with IXP Manager.  If not, see:
 *
 * http://www.gnu.org/licenses/gpl-2.0.html
 */


/**
 * A trait of common functions
 *
 * @author     Barry O'Donovan <barry@opensolutions.ie>
 * @category   IXP
 * @package    IXP_Controller_Trait
 * @copyright  Copyright (c) 2009 - 2013, Internet Neutral Exchange Association Ltd
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPL V2.0
 */
trait IXP_Controller_Trait_Common
{
    /**
     * Checks if reseller mode is enabled.
     *
     * To enable resller mode set reseller.enable to true in application.ini
     *
     * @see https://github.com/inex/IXP-Manager/wiki/Reseller-Functionality
     *
     * @return bool
     */
    protected function resellerMode()
    {
        return ( isset( $this->_options['reseller']['enabled'] ) && $this->_options['reseller']['enabled'] );
    }
    
    /**
     * Checks if multi IXP mode is enabled.
     *
     * To enable multi IXP mode set multiixp.enable to true in application.ini
     *
     * @see https://github.com/inex/IXP-Manager/wiki/Multi-IXP-Functionality
     *
     * @return bool
     */
    protected function multiIXP()
    {
        return ( isset( $this->_options['multiixp']['enabled'] ) && $this->_options['multiixp']['enabled'] );
    }
    
    /**
     * Load a customer from the database by shortname but redirect to `error/error` if no such customer.
     *
     * Will use 'shortname' parameter is no shortname provided
     *
     * @param string|bool $shortname The customer shortname to load (or, if false, look for `shortname` parameter)
     * @param string $redirect Alternative location to redirect to
     * @return \Entities\Customer The customer object
     */
    protected function loadCustomerByShortname( $shortname = false, $redirect = null )
    {
        if( $shortname === false )
            $shortname = $this->getParam( 'shortname', false );
    
        if( $shortname )
            $c = $this->getD2EM()->getRepository( '\\Entities\\Customer' )->findOneBy( [ 'shortname' => $shortname ] );
    
        if( !$shortname || !$c )
        {
            $this->addMessage( 'Invalid customer', OSS_Message::ERROR );
            $this->redirect( $redirect === null ? 'error/error' : $redirect );
        }
    
        return $c;
    }
    
    /**
     * Load a customer from the database by ID but redirect to `error/error` if no such customer.
     *
     * @param int $id The customer ID to load
     * @param string $redirect Alternative location to redirect to
     * @return \Entities\Customer The customer object
     */
    protected function loadCustomerById( $id, $redirect = null )
    {
        if( $id )
            $c = $this->getD2R( '\\Entities\\Customer' )->find( $id );
    
        if( !$id || !$c )
        {
            $this->addMessage( "Could not load the requested customer object", OSS_Message::ERROR );
            $this->redirect( $redirect === null ? 'error/error' : $redirect );
        }
    
        return $c;
    }
    
    /**
     * Load an IXP from the database by ID but redirect to `error/error` if no such IXP.
     *
     * @param int $id The IXP ID to load
     * @param string $redirect Alternative location to redirect to (if null, `error/error`, if false, return false on error)
     * @return \Entities\IXP The IXP object
     */
    protected function loadIxpById( $id, $redirect = null )
    {
        $i = $this->getD2R( '\\Entities\\IXP' )->find( $id );
    
        if( !$id || !$i )
        {
            if( $redirect === false )
                return false;
            
            $this->addMessage( "Could not load the IXP object", OSS_Message::ERROR );
            $this->redirect( $redirect === null ? '' : $redirect );
        }
        
        return $i;
    }
    
}

