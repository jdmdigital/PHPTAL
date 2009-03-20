<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//  
//  Copyright (c) 2004-2005 Laurent Bedubourg
//  
//  This library is free software; you can redistribute it and/or
//  modify it under the terms of the GNU Lesser General Public
//  License as published by the Free Software Foundation; either
//  version 2.1 of the License, or (at your option) any later version.
//  
//  This library is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
//  Lesser General Public License for more details.
//  
//  You should have received a copy of the GNU Lesser General Public
//  License along with this library; if not, write to the Free Software
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
//  
//  Authors: Laurent Bedubourg <lbedubourg@motion-twin.com>
//  

require_once PHPTAL_DIR.'PHPTAL/Php/Attribute.php';

// METAL Specification 1.0
//
//      argument ::= Name
//
// Example:
//
//      <p metal:define-macro="copyright">
//      Copyright 2001, <em>Foobar</em> Inc.
//      </p>
//
// PHPTAL:
//      
//      <?php function XXX_macro_copyright( $tpl ) { ? >
//        <p>
//        Copyright 2001, <em>Foobar</em> Inc.
//        </p>
//      <?php } ? >
//

/**
 * @package phptal.php.attribute.metal
 * @author Laurent Bedubourg <lbedubourg@motion-twin.com>
 */
class PHPTAL_Php_Attribute_METAL_DefineMacro extends PHPTAL_Php_Attribute
{
    public function start(PHPTAL_Php_CodeWriter $codewriter)
    {
        $macroname = strtr(trim($this->expression),'-','_');
        if (!preg_match('/^[a-z0-9_]+$/i', $macroname)){
            throw new PHPTAL_ParserException('Bad macro name "'.$macroname.'"', $this->phpelement->getSourceFile(), $this->phpelement->getSourceLine());
        }
        
        $codewriter->doFunction($macroname, 'PHPTAL $_thistpl, PHPTAL $tpl');
        $codewriter->doSetVar('$tpl', 'clone $tpl');
        $codewriter->doSetVar('$ctx', '$tpl->getContext()');
        $codewriter->doSetVar('$glb', '$tpl->getGlobalContext()');
        $codewriter->doSetVar('$_translator', '$tpl->getTranslator()');
        $codewriter->doXmlDeclaration();
        $codewriter->doDoctype();
    }
    
    public function end(PHPTAL_Php_CodeWriter $codewriter)
    {
        $codewriter->doEnd();
    }
}

