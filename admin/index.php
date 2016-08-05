<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: index.php
 * $Id: index.php v 1.0 2016-06-22 14:36:58 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-06-22 16:41:37 $
 * @brief
 *
 ******************************************************************/

use yii\helpers\Url;
?>
<iframe src="<?= Url::to(['/user/admin'])?>" frameborder="0" name="mainframe" id="mainframe" ></iframe>
