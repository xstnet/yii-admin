<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@static', dirname(dirname(__DIR__)) . '/static');
//Yii::setAlias('@static_backend', dirname(dirname(__DIR__)) . '/static/backend');
Yii::setAlias('@static_backend', './static/backend');
Yii::setAlias('@static_frontend', './static/frontend');
