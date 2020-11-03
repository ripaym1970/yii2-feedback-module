yii2-feedback-module
===================
Модуль обратной связи

Установка
------------
Выполнить команду

```
php composer.phar require --prefer-dist egor260890/yii2-feedback-module "*"
```

Или добавить в `composer.json`.

```
"egor260890/yii2-feedback-module": "*"
```
И выполнить
```$xslt
composer update
```
Выполнить миграции
```
yii migrate --migrationPath=@egor260890/feedback/migrations/
```

Использование
-----

Подключаем модуль просмотра сообщений:
```$xslt
'modules' => [
    'feedback' => [
        'class' => 'egor260890\feedback\Module',
    ],
],
```
На фронтенде:
```$xslt
'modules'=>[
    'feedback-send' => [
        'class' => 'egor260890\feedback\widgets\Module', 
    ],
],
```
Для вывода формы обратной связи:
```php
<?=\egor260890\feedback\widgets\FeedbackForm::widget([
            'id'=>'feed',
            'template'=>'{name}{tel}{email}{company_name}{message}{button}',
            'rules'=>function(){
                return [
                    [['tel'], 'required','message'=>'custom message'] //можно задать правила валидации
                ];
            },
            'fieldsConfig'=>[ //настройка полей
                'name'=>[
                    'template'=>'{input}', 
                    'placeholder'=>'please'
                ],
                'tel'=>[
                    'label'=>'custom label'
                ],
                'button'=>[
                    'label'=>'custom button name',
                    'class'=>'btn btn-warning'
                ]
            ],
            'formConfig'=>[
                'enableAjaxValidation'   => true,
                'enableClientValidation' => true,
            ]
        ])?>
```


Отслеживание события отправки
-----------------------------

Создаем слушатель
```$xslt
class Observer implements FeedbackObserverInterface{
   
}
```

Подключаем его в настройках модуля
```$xslt
'modules' => [
    'feedback-send' => [
        'class' => 'egor260890\feedback\widgets\Module',
        'observers' => [
            \mypath\Observer:class,
            \mypath\Observer2:class,
        ]
        //либо
        'observers' => \mypath\Observer:class,
        //либо 
        'observers' => function(){
            return new Observer();
        },
    ],
],
```

Коммит и заливка на Git
-----------------------
1. Проверить ветку - должна быть "fix"
2. Выполнить commit изменений
3. Выполнить 
```
git push origin fix
```
4. На личном/релизе в проекте выполнить
```
composer update --ignore-platform-reqs
```

Теперь сделаем так, чтобы корректно работал IDE без установки Yii2 в расширение.
Для этого необходимо в каталоге с виджетом добавить «symbolic link» на vendor
```
mklink /D C:\OSPanel\domains\yii2-feedback-module\vendor C:\OSPanel\domains\adv.loc\vendor
```
Заметка: Для того, чтобы проверить работоспособность созданных «symbolic link» необходимо «двойным щелчком левой кнопки мыши» кликнуть по каждому из них. Если переход в соотвествующие каталоги (файлы) произошел успешно – то соответственно Вы создали корректные «symbolic link». 
