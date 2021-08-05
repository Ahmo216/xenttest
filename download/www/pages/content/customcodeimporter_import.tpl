<div id="tabs">
    <div id="tabs-1">
        [ERROR]
        [MESSAGE]
        <div class="row">
            <div class="row-height">
                <div class="col-xs-12 col-md-12 col-md-height">
                    <div class="inside inside-full-height">
                        <fieldset>
                            <p>
                                Import custom code into the xentral instance. Code can be imported only
                                from one repository. Switching to a new repository will remove all the
                                custom code imported from the previous one.
                            </p>

                            <form action="index.php?module=customcodeimporter&action=import" method="POST">
                                <div class="input">
                                    <label for="url">Git repository URL:</label><br />
                                    <input type="url" name="url" size="50" pattern="https://.*" required />
                                    <div class="help">Enter in format: https://host.com/repository-name</div>
                                </div>

                                <div class="input">
                                    <label for="privateKey">Private key:</label><br />
                                    <textarea name="privateKey" rows="4" cols="50"></textarea>
                                    <div class="help">Required only if the git repository is private</div>
                                </div>

                                <input type="submit">
                            </form>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
