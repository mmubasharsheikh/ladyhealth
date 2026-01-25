<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\AppointmentRepositoryInterface;
use App\Repositories\Eloquent\AppointmentRepository;
use DoctorAvailabilityRepository;
use DoctorAvailabilityRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            AppointmentRepositoryInterface::class,
            AppointmentRepository::class
        );

        $this->app->bind(
            DoctorAvailabilityRepositoryInterface::class,
            DoctorAvailabilityRepository::class
        );
    }
}
