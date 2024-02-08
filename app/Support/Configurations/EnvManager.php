<?php

namespace App\Support\Configurations;

use App\Contracts\InteractsWithEnv;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;

class EnvManager implements InteractsWithEnv
{
    protected readonly \Jackiedo\DotenvEditor\DotenvEditor $editor;

    protected bool $pending = false;

    function __construct(
        public readonly string $path,
    ){
        $this->editor = DotenvEditor::load($this->path);
    }

    public function putEnvFile(array $data, bool $flush = false)
    {
        $this->editor->setKeys($data);
        if($flush) {
            $this->editor->save();
        } else {
            $this->pending = true;
        }

    }

    public function updateEnvFile(string $key, string $value, bool $flush = false)
    {
        $this->editor->setKey($key, $value);
        if($flush) {
            $this->editor->save();
        } else {
            $this->pending = true;
        }
    }

    public function flush(): void {
        if($this->pending) {
            info("updating env file with pending changes");
            $this->editor->save();
        }
    }

}
