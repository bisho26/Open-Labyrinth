<?php
/**
 * Open Labyrinth [ http://www.openlabyrinth.ca ]
 *
 * Open Labyrinth is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Open Labyrinth is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Open Labyrinth.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright Copyright 2012 Open Labyrinth. All Rights Reserved.
 *
 */ ?>
<div class="page-header">
    <div class="pull-right">
        <div class="btn-group">
            <a class="btn btn-primary" href="<?php echo URL::base().'webinarManager/add'; ?>"><i class="icon-plus-sign icon-white"></i>Create Scenario</a>
            <a class="btn btn-primary" href="<?php echo URL::base().'webinarManager/allConditions'; ?>"><i class="icon-plus-sign icon-white"></i>Conditions</a>
        </div>
    </div>
    <h1><?php echo __('Scenarios'); ?></h1>
</div>

<table class="table table-striped table-bordered" id="my-labyrinths">
    <colgroup>
        <col style="width: 50%" />
        <col style="width: 20%" />
        <col style="width: 30%" />
    </colgroup>
    <thead>
        <tr>
            <th><?php echo __('Scenario Title'); ?></th>
            <th><?php echo __('Step'); ?></th>
            <th><?php echo __('Actions'); ?></th>
        </tr>
    </thead>
    <tbody><?php
    if(isset($templateData['webinars']) && count($templateData['webinars'])) {
        foreach($templateData['webinars'] as $scenario) { ?>
        <tr>
            <td><a href="<?php echo URL::base(); ?>"><?php echo $scenario->title; ?></a></td>
            <td><?php
                if($scenario->current_step != null AND count($scenario->steps)) {
                    foreach($scenario->steps as $scenarioStep) {
                        if($scenarioStep->id == $scenario->current_step) {
                            echo $scenarioStep->name;
                            break;
                        }
                    }
                }
                else echo '-'; ?>
            </td>
            <td class="center">
                <div class="btn-group">
                    <a class="btn btn-success" href="<?php echo URL::base().'webinarManager/progress/'.$scenario->id; ?>">
                        <i class="icon-play icon-white"></i><span class="visible-desktop">View progress</span>
                    </a><?php
                    if($scenario->forum_id) { ?>
                    <a class="btn btn-info" href="<?php echo URL::base(); ?><?php if ($scenario->isForum) {?>dforumManager/viewForum/<?php } else {?>dtopicManager/viewTopic/<?php } ?><?php echo $scenario->forum_id; ?>">
                        <i class="icon-list-alt"></i><span class="visible-desktop">Forum</span>
                    </a><?php
                    } ?>
                    <a data-toggle="modal" href="javascript:void(0)" data-target="#change-step-<?php echo $scenario->id; ?>" class="btn btn-info"><i class="icon-edit icon-white"></i>Change step</a>
                    <a class="btn btn-info" href="<?php echo URL::base().'webinarManager/edit/'.$scenario->id; ?>"><i class="icon-edit icon-white"></i>Edit</a>
                    <a class="btn btn-info" href="<?php echo URL::base().'webinarManager/visualEditor/'.$scenario->id; ?>"><i class="icon-edit icon-white"></i>Visual editor</a>
                    <a class="btn btn-info" href="<?php echo URL::base().'webinarManager/statistics/'.$scenario->id; ?>"><i class="icon-calendar icon-white"></i>Statistics</a>
                    <a data-toggle="modal" href="javascript:void(0)" data-target="#reset-webinar-<?php echo $scenario->id; ?>" class="btn btn-warning">
                        <i class="icon-refresh icon-white"></i><?php echo __('Reset'); ?>
                    </a><?php
                    if (Auth::instance()->get_user()->type->name != 'Director') { ?>
                    <a data-toggle="modal" href="javascript:void(0)" data-target="#delete-node-<?php echo $scenario->id; ?>" class="btn btn-danger">
                        <i class="icon-trash icon-white"></i><?php echo __('Delete'); ?>
                    </a><?php
                    } ?>
                </div>
                <div class="modal hide alert alert-block alert-error fade in" id="delete-node-<?php echo $scenario->id; ?>">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="alert-heading"><?php echo __('Caution! Are you sure?'); ?></h4>
                    </div>
                    <div class="modal-body">
                        <p><?php echo __('You have just clicked the delete button, are you certain that you wish to proceed with deleting "' . $scenario->title . '"?'); ?></p>
                        <p>
                            <a class="btn btn-danger" href="<?php echo URL::base(); ?>webinarManager/delete/<?php echo $scenario->id; ?>"><?php echo __('Delete'); ?></a> <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                        </p>
                    </div>
                </div>
                <div class="modal hide fade in" id="change-step-<?php echo $scenario->id; ?>">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="alert-heading"><?php echo __('Select step'); ?></h4>
                    </div>
                    <div class="modal-body">
                        <p><?php
                        if(count($scenario->steps)) {
                            foreach($scenario->steps as $scenarioStep) { ?>
                            <div>
                                <input class="current-step-<?php echo $scenario->id; ?>" type="radio" name="currentStep<?php echo $scenario->id; ?>" value="<?php echo $scenarioStep->id; ?>" <?php if($scenario->current_step == $scenarioStep->id) echo 'checked'; ?>>
                                <?php echo $scenarioStep->name; ?>
                            </div><?php
                            }
                        } ?>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <a class="btn change-step-btn" href="<?php echo URL::base().'webinarManager/changeStep/'.$scenario->id.'/'; ?>" webinarId="<?php echo $scenario->id; ?>"><?php echo __('Change'); ?></a>
                        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                    </div>
                </div>

                <div class="modal hide fade in alert alert-block alert-danger" id="reset-webinar-<?php echo $scenario->id; ?>">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="alert-heading"><?php echo __('Reset scenario'); ?></h4>
                    </div>
                    <div class="modal-body">
                        <p>
                            <?php echo __('Warning! Do you really want to reset scenario? If you reset webinar all user sessions which created on playing this scenarion will be delete.'); ?>
                        </p>
                        <div>
                            <a class="btn btn-danger" href="<?php echo URL::base().'webinarManager/reset/'.$scenario->id; ?>"><?php echo __('Reset'); ?></a>
                            <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                        </div>
                    </div>

                </div>
            </td>
        </tr><?php
        }
    } else { ?>
        <tr class="info"><td colspan="4">There are no available scenarios right now. You may add a scenarios using the add button.</td></tr><?php
    } ?>
    </tbody>
</table>

<script>
    $(function() {
        $('.change-step-btn').click(function() {
            var webinarId = $(this).attr('webinarId'),
                step      = $('.current-step-' + webinarId + ':checked').val();

            $(this).attr('href', $(this).attr('href') + step);
        });
    })
</script>