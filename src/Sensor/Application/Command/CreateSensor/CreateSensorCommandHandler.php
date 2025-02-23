<?php

namespace App\Sensor\Application\Command\CreateSensor;

use App\Sensor\Domain\Entity\Sensor;
use App\Sensor\Domain\Repository\SensorRepositoryInterface;
use App\Sensor\Exception\SensorAlreadyExistsException;
use App\Shared\Domain\Bus\HandlerInterface;

readonly class CreateSensorCommandHandler implements HandlerInterface
{
    public function __construct(private SensorRepositoryInterface $repository)
    {
    }

    public function __invoke(CreateSensorCommand $command): void
    {
        if (null !== $this->repository->findByName($command->name)) {
            throw new SensorAlreadyExistsException();
        }

        $this->repository->save(new Sensor(
            name: $command->name
        ));
    }
}
