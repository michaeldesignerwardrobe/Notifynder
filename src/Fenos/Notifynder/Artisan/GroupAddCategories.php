<?php namespace Fenos\Notifynder\Artisan;

use Fenos\Notifynder\Groups\NotifynderGroup;
use Fenos\Notifynder\Parse\ArtisanOptionsParser;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class GroupAddCategories extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'notifynder:group-add-categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Associate the categories to a group';

    /**
     * @var NotifynderGroup
     */
    private $notifynderGroup;

    /**
     * @var ArtisanOptionsParser
     */
    private $artisanOptionsParser;

    /**
     * Create a new command instance.
     *
     * @param  \Fenos\Notifynder\Groups\NotifynderGroup     $notifynderGroup
     * @param  ArtisanOptionsParser                         $artisanOptionsParser
     * @return \Fenos\Notifynder\Artisan\GroupAddCategories
     */
    public function __construct(NotifynderGroup $notifynderGroup,
                                ArtisanOptionsParser $artisanOptionsParser)
    {
        parent::__construct();
        $this->notifynderGroup = $notifynderGroup;
        $this->artisanOptionsParser = $artisanOptionsParser;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $arguments = $this->getArgumentsConsole();

        $groupCategories = $this->notifynderGroup->addMultipleCategoriesToGroup($arguments);

        if ($groupCategories) {
            foreach ($groupCategories->categories as $category) {
                $this->info("Category {$category->name} has been associated to the group {$groupCategories->name}");
            }
        } else {
            $this->error('The name must be a string with dots as namespaces');
        }
    }

    /**
     * @return array|string
     */
    public function getArgumentsConsole()
    {
        $names = $this->argument('name');

        $categories = $this->option('categories');

        $categories = $this->artisanOptionsParser->parse($categories);

        array_unshift($categories, $names);

        return $categories;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('name', InputArgument::REQUIRED, 'user.post.add'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('categories', null, InputOption::VALUE_OPTIONAL, 'notifynder.name', []),
        );
    }
}
