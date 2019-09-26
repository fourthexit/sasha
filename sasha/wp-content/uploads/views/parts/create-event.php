<!--
This is a HTML script for a User form to create events. It is partitioned in divisions
with each holding specific information about details of the event. It includes
snippets of AngularJS and Bootstrap
-->
<div class="row hidden" ng-controller="createEventCtrl" id="createEventCtrl">
    <div class="col-md-12">
        <h1 class="type-header">Create an event</h1>

        <form ng-submit="createEvent()" id="EventDetailsForm">
            <fieldset>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label>Title: </label>
                        <input type="text" class="form-control" name="event_title" ng-model="event_title"><br>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label>Content: </label>
                        <?php
                        wp_editor("", "tribe_event_details", array(
                            'editor_class' => "form-control"
                        ));
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label>Event URL: </label>
                        <input type="text" class="form-control" name="event_url" ng-model="event_url"><br>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <h3>Event Details</h3>

                        <label>TIME & DATE <small>(Start/End)</small>:</label>
                        <div>

                            <div class="dates-wrapper">

                                <div class="disp-date-wrapper">
                                    <div class="form-group row no-margin">
                                        <div class="col-md-6 col-xs-6 no-padding">
                                            <input autocomplete="off" type="text" class="tribe-datepicker tribe-field-start_date hasDatepicker" name="EventStartDate" id="EventStartDate" placeholder="mm/dd/yyyy">
                                        </div>
                                        <div class="col-md-6 col-xs-6 no-padding">
                                            <input autocomplete="off" type="text" class="tribe-timepicker tribe-field-start_time ui-timepicker-input" name="EventStartTime" id="EventStartTime" data-step="30" data-round="" placeholder="hh:mm:ss">
                                        </div>
                                    </div>
                                </div>
                                <div class="tribe-datetime-separator"> to </div>
                                <div class="disp-date-wrapper">
                                    <div class="form-group row no-margin">
                                        <div class="col-md-6 col-xs-6 no-padding">
                                            <input autocomplete="off" type="text" class="tribe-datepicker tribe-field-end_date hasDatepicker" name="EventEndDate" id="EventEndDate" placeholder="mm/dd/yyyy">
                                        </div>
                                        <div class="col-md-6 col-xs-6 no-padding">
                                            <input autocomplete="off" type="text" class="tribe-timepicker tribe-field-end_time ui-timepicker-input" name="EventEndTime" id="EventEndTime"  data-step="30" data-round="" placeholder="hh:mm:ss" >
                                        </div>
                                    </div>
                                </div>

                            </div>




                            <div class="form-group">
                                <p class="tribe-allday">
                                    <input tabindex="2003" type="checkbox" id="EventAllDay" name="EventAllDay" value="yes">
                                    <label for="EventAllDay">All Day Event</label>
                                </p>
                            </div>


                        </div>

                        <div>


                            <div class='new_venue'>
                                <div class="row">
                                    <div class="form-group col-md-12" >
                                        <label>Venue Address: </label>
                                        <input class="form-control" type="text" name="venue_address" id="venue_address" size="25" value="" aria-label="Venue Address">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group  col-md-6">
                                        <label>City: </label>
                                        <input class="form-control" type="text" name="venue_city" id="venue_city" size="25" value="" aria-label="City">
                                    </div>
                                    <div class="form-group  col-md-6">
                                        <label>Country: </label>
                                        <input class="form-control "  type="text" name="venue_country" id="venue_country" size="25" value="" aria-label="Country">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="form-group col-md-12">
                        <h4>EVENT COST</h4>

                        <div class="row">


                            <div class="form-group col-md-3">
                                <label>Currency Symbol: </label>
                                <input class="form-control " type="text" name="currency_symbol" id="currency_symbol" value="">
                            </div>
                            <div class="form-group col-md-5">
                                <label>Cost: </label>
                                <input class="form-control" type="text" id="event_cost" name="event_cost" size="25" value="">
                            </div>
                            <div class="form-group col-md-12">
                                <small><i>Enter a 0 for events that are free or leave blank to hide the field.</i></small>
                            </div>

                        </div>


                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <h3>Topic Folders</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <div ng-repeat="topic in topics" class='row' id='topics_list'>
                            <div class="col-md-12">
                                <h4 ng-bind="topic.title"></h4>
                                <div ng-repeat="folder in topic.folders">
                                    <label>
                                        <input type="checkbox" name ='final_selected_topics[]' value="{{folder.id}}" id='{{folder.id}}'/>
                                        {{folder.title}}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                 <div class="row">
                    <div class="form-group col-md-12">
                        <input type="submit" class="btn btn-primary"  value="Submit"/>
                    </div>
                </div>



            </fieldset>
        </form>
    </div>
</div>
