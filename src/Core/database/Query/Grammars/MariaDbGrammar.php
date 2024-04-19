<?php

namespace NINA\Database\Query\Grammars;

use NINA\Database\Query\Builder;
use NINA\Database\Query\JoinLateralClause;
use RuntimeException;

class MariaDbGrammar extends MySqlGrammar
{
    /**
     * Compile a "lateral join" clause.
     *
     * @param  \NINA\Database\Query\JoinLateralClause  $join
     * @param  string  $expression
     * @return string
     *
     * @throws \RuntimeException
     */
    public function compileJoinLateral(JoinLateralClause $join, string $expression): string
    {
        throw new RuntimeException('This database engine does not support lateral joins.');
    }

    /**
     * Determine whether to use a legacy group limit clause for MySQL < 8.0.
     *
     * @param  \NINA\Database\Query\Builder  $query
     * @return bool
     */
    public function useLegacyGroupLimit(Builder $query)
    {
        return false;
    }
}
