<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ReflectionClass;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Symfony\Component\Finder\SplFileInfo;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $roles = collect([
            'super admin',
        ]);

        $models = collect(File::allFiles(app_path()))
            ->map(function (SplFileInfo $info) {
                $path = $info->getRelativePathname();

                return sprintf('\%s%s',
                    app()->getNamespace(),
                    Str::replace('/', '\\', Str::beforeLast($path, '.')));

            })
            ->filter(function (string $class) {
                try {
                    $reflection = new ReflectionClass($class);
                } catch (\ReflectionException $throwable) {
                    return false;
                }

                return $reflection->isSubclassOf(Model::class) &&
                    !$reflection >isAbstract();
            })
            ->map(function ($model) {
                return Str::lower(Str::plural(Str::afterLast($model, '\\')));
            })
            ->map(function ($model) {
                return [
                    "create {$model}",
                    "update {$model}",
                    "delete {$model}",
                    "restore {$model}",
                    "view {$model}",
                    "force delete {$model}",
                ];
            });


        $roles->each(function ($role) use ($models) {
            $role = Role::create(['name' => $role]);
        });
    }
}

