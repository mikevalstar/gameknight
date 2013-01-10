{extends 'layout.tpl'}
{block name="content"}

<h2 class="pull-left">Mass Mailer</h2>

<div class="clearfix"></div>

<form method="post">
    <input type="hidden" name="action" value="save" />
    <div class="row">
        <div class="span12">
            <div class="control-group">
                <label class="control-label" for="subject">Subject</label>
                <div class="controls">
                    <input type="text" id="subject" name="subject" class="span8 required" value="" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="message">Message</label>
                <div class="controls">
                    <textarea id="message" class="span8" name="message" rows="10"></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="span12">
          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Send</button>
          </div>
        </div>
    </div>
</div>

{/block}
