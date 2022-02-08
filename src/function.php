<?php
/**
 * This file is part of heros-util.
 *
 * @contact  mondagroup_php@163.com
 *
 */
const HEROS_UTILS_VERSION = '0.0.1';

if (! function_exists('heros_util_version')) {
    function heros_util_version(): string
    {
        return HEROS_UTILS_VERSION;
    }
}

if (! function_exists('print_line')) {
    function print_line($msg)
    {
        echo "{$msg} \n";
    }
}

/*
 * 终端高亮打印绿色
 * @param $message
 */
if (! function_exists('print_ok')) {
    function print_ok($message)
    {
        printf("\033[32m\033[1m{$message}\033[0m\n");
    }
}

/*
 * 终端高亮打印红色
 * @param $message
 */
if (! function_exists('print_error')) {
    function print_error($message)
    {
        printf("\033[31m\033[1m{$message}\033[0m\n");
    }
}

/*
 * 终端高亮打印黄色
 * @param $message
 */
if (! function_exists('print_warning')) {
    function print_warning($message)
    {
        printf("\033[33m\033[1m{$message}\033[0m\n");
    }
}

/**
 * 计算CPU数量
 * @return int
 */
if (! function_exists('cpu_count')) {
    function cpu_count(): int
    {
        // Windows does not support the number of processes setting.
        if (\DIRECTORY_SEPARATOR === '\\') {
            return 1;
        }
        if (strtolower(PHP_OS) === 'darwin') {
            $count = shell_exec('sysctl -n machdep.cpu.core_count');
        } else {
            $count = shell_exec('nproc');
        }
        return (int)$count > 0 ? (int)$count : 4;
    }
}

/**
 * copy 目录
 */
if (! function_exists('copy_dir')) {
    function copy_dir(string $source, string $des)
    {
        if (is_dir($source)) {
            if (! is_dir($des)) {
                mkdir($des);
            }
            $files = scandir($source);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    copy_dir("$source/$file", "$des/$file");
                }
            }
        } elseif (file_exists($source)) {
            copy($source, $des);
        }
    }
}

/**
 * 删除目录
 */
if (function_exists('remove_dir')) {
    function remove_dir(string $dir): bool
    {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            (is_dir("$dir/$file") && ! is_link($dir)) ? remove_dir("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }
}
