<?php

namespace App\Form\FormExtension;

use Psr\Log\LoggerInterface;
use Symfony\Component\Form\AbstractType;
use App\EventSubscriber\HoneyPotSubscriber;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class HoneyPotType extends AbstractType
{
    private $honeyPotLogger;

    private $requestStack;

    public function __construct(LoggerInterface $honeyPotLogger, RequestStack $requestStack)
    {
        $this->honeyPotLogger = $honeyPotLogger;
        $this->requestStack = $requestStack;
    }

    protected const FIRST_HONEY_POT_FOR_BOT = "landLinePhone";

    protected const SECOND_HONEY_POT_FOR_BOT = "company";

    protected const THIRD_HONEY_POT_FOR_BOT = "subject";

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIRST_HONEY_POT_FOR_BOT, TextType::class, $this->setHoneyPotConfiguration())
                ->add(self::SECOND_HONEY_POT_FOR_BOT, TextType::class, $this->setHoneyPotConfiguration())
                ->add(self::THIRD_HONEY_POT_FOR_BOT, TextType::class, $this->setHoneyPotConfiguration())
                ->addEventSubscriber(new HoneyPotSubscriber($this->honeyPotLogger, $this->requestStack));
    }

    protected function setHoneyPotConfiguration(): array
    {
        return [
            'attr'   => [
                'autocomplete' => 'off',
                'tabindex' => '-1'
            ],
            'mapped' => false,
            'required' => false
        ];
    }
}
