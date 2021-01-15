<?php


namespace App\Repository\DQL;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;

class UnaccentDQL extends FunctionNode
{
    private $string;

    public function getSql(SqlWalker $sqlWalker)
    {
        return 'UNACCENT(' . $this->string->dispatch($sqlWalker) .")";
    }

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->string = $parser->StringPrimary();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

}