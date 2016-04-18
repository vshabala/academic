<?php

namespace Oro\Bundle\IssueBundle\Migrations\Schema\v1_4;

use Doctrine\DBAL\Schema\Schema;

use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class OroIssueBundle implements Migration
{

    /**
     * @var string
     */
    protected $issueTableName = 'oro_issue';

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $table = $schema->getTable($this->issueTableName);

        $table->addColumn('issue_type', 'string', ['notnull' => false, 'length' => 32]);
    }
}
