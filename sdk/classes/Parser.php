<?php

/*
 * Copyright 2017 Christoph M. Becker
 *
 * This file is part of the Pfw_XH SDK.
 *
 * The Pfw_XH SDK is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * The Pfw_XH SDK is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with the Pfw_XH SDK.  If not, see <http://www.gnu.org/licenses/>.
 */

class Parser
{
    /**
     * @var Scanner
     */
    private $scanner;

    /**
     * @var Symbol
     */
    private $symbol;

    /**
     * @param string $filename
     */
    public function __construct($filename)
    {
        $this->scanner = new Scanner($filename);
    }

    /**
     * @return void
     */
    public function parse()
    {
        $this->symbol = $this->scanner->getNext();
        while (true) {
            switch ($this->symbol->getKind()) {
                case T_INLINE_HTML:
                    $this->accept(T_INLINE_HTML);
                    break;
                case T_OPEN_TAG;
                    $this->accept(T_OPEN_TAG);
                    $this->parseStatement();
                    $this->accept(T_CLOSE_TAG);
                    break;
                case T_OPEN_TAG_WITH_ECHO:
                    $this->accept(T_OPEN_TAG_WITH_ECHO);
                    $this->parseExpression();
                    $this->accept(T_CLOSE_TAG);
                    break;
                case -1:
                    return;
                default:
                    $this->error();
            }
        }
    }

    /**
     * @return void
     */
    private function parseStatement()
    {
        switch ($this->symbol->getKind()) {
            case T_FOREACH:
                $this->parseForeach();
                break;
            case T_ENDFOREACH:
                $this->accept(T_ENDFOREACH);
                break;
            case T_IF:
                $this->parseIf();
                break;
            case T_ELSEIF:
                $this->parseElseif();
                break;
            case T_ELSE:
                $this->accept(T_ELSE);
                $this->accept(':');
                break;
            case T_ENDIF:
                $this->accept(T_ENDIF);
                break;
            default:
                $this->error();
        }
    }

    /**
     * @return void
     */
    private function parseForeach()
    {
        $this->accept(T_FOREACH);
        $this->accept('(');
        $this->parseExpression();
        $this->accept(T_AS);
        $this->accept(T_VARIABLE);
        $this->accept(')');
        $this->accept(':');
    }

    /**
     * @return void
     */
    private function parseIf()
    {
        $this->accept(T_IF);
        $this->accept('(');
        $this->parseExpression();
        $this->accept(')');
        $this->accept(':');
    }

    /**
     * @return void
     */
    private function parseElseif()
    {
        $this->accept(T_ELSEIF);
        $this->accept('(');
        $this->parseExpression();
        $this->accept(')');
        $this->accept(':');
    }

    /**
     * @return void
     */
    private function parseExpression()
    {
        switch ($this->symbol->getKind()) {
            case T_CONSTANT_ENCAPSED_STRING:
                $this->accept(T_CONSTANT_ENCAPSED_STRING);
                break;
            case T_VARIABLE:
                $this->accept(T_VARIABLE);
                while ($this->symbol->getKind() === T_OBJECT_OPERATOR) {
                    $this->accept(T_OBJECT_OPERATOR);
                    $this->accept(T_STRING);
                    if ($this->symbol->getKind() === '(') {
                        $this->accept('(');
                        if ($this->symbol->getKind() !== ')') {
                            $this->parseExpression();
                        }
                        while ($this->symbol->getKind() === ',') {
                            $this->accept(',');
                            $this->parseExpression();
                        }
                        $this->accept(')');
                    }
                }
                break;
            default:
                $this->error();
        }
    }

    /**
     * @param int|string $kind
     */
    private function accept($kind)
    {
        if ($this->symbol->getKind() !== $kind) {
            $this->error();
        }
        $this->symbol = $this->scanner->getNext();
    }

    /**
     * @return void
     */
    private function error()
    {
        echo realpath($this->scanner->getFilename()), ':', $this->symbol->getLine(), ': unexpected ', $this->symbol->getName(), PHP_EOL;
        exit(1);
    }
}
