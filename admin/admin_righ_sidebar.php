
                <!-- .right-sidebar (modernized) -->
                <aside class="right-sidebar kaya-card" aria-label="Service panel">
                    <div class="slimscrollright">
                        <header class="rpanel-title" style="display:flex;justify-content:space-between;align-items:center">
                            <strong>Service Panel</strong>
                            <button class="btn btn-sm btn-link right-side-toggle" aria-label="Close settings" title="Close settings" style="color:inherit;border:0;background:transparent"><i class="ti-close"></i></button>
                        </header>

                        <div class="r-panel-body p-3">
                            <form aria-labelledby="service-panel" role="group">
                                <fieldset style="border:0;padding:0;margin:0">
                                    <legend id="service-panel" class="sr-only">Layout Options</legend>
                                    <div class="form-group mb-2">
                                        <label style="display:flex;gap:8px;align-items:center">
                                            <input id="checkbox1" type="checkbox" class="fxhdr" aria-checked="false">
                                            <span>Fix Header</span>
                                        </label>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label style="display:flex;gap:8px;align-items:center">
                                            <input id="checkbox2" type="checkbox" checked class="fxsdr" aria-checked="true">
                                            <span>Fix Sidebar</span>
                                        </label>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label style="display:flex;gap:8px;align-items:center">
                                            <input id="checkbox4" type="checkbox" class="open-close" aria-checked="false">
                                            <span>Toggle Sidebar</span>
                                        </label>
                                    </div>
                                </fieldset>
                            </form>

                            <div id="themecolors" class="m-t-20">
                                <div style="font-weight:600;margin-bottom:8px">With Light sidebar</div>
                                <div style="display:flex;gap:8px;flex-wrap:wrap">
                                    <button class="theme-btn default-theme" data-theme="default" aria-label="Apply default theme">1</button>
                                    <button class="theme-btn green-theme" data-theme="green" aria-label="Apply green theme">2</button>
                                    <button class="theme-btn yellow-theme" data-theme="gray" aria-label="Apply gray theme">3</button>
                                    <button class="theme-btn blue-theme working" data-theme="blue" aria-label="Apply blue theme">4</button>
                                    <button class="theme-btn purple-theme" data-theme="purple" aria-label="Apply purple theme">5</button>
                                    <button class="theme-btn megna-theme" data-theme="megna" aria-label="Apply megna theme">6</button>
                                </div>

                                <div style="font-weight:600;margin:12px 0 8px">With Dark sidebar</div>
                                <div style="display:flex;gap:8px;flex-wrap:wrap">
                                    <button class="theme-btn default-dark-theme" data-theme="default-dark">7</button>
                                    <button class="theme-btn green-dark-theme" data-theme="green-dark">8</button>
                                    <button class="theme-btn yellow-dark-theme" data-theme="gray-dark">9</button>
                                    <button class="theme-btn blue-dark-theme" data-theme="blue-dark">10</button>
                                    <button class="theme-btn purple-dark-theme" data-theme="purple-dark">11</button>
                                    <button class="theme-btn megna-dark-theme" data-theme="megna-dark">12</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>

                <script>
                (function(){
                  // close panel control
                  var closeBtns = document.querySelectorAll('.right-side-toggle');
                  closeBtns.forEach(function(b){
                    b.addEventListener('click', function(e){
                      e.preventDefault();
                      document.body.classList.remove('sidebar-open');
                    });
                  });

                  // theme buttons: simple demo that toggles data-theme attr on body
                  var themeBtn = document.querySelectorAll('#themecolors .theme-btn');
                  themeBtn.forEach(function(tb){
                    tb.addEventListener('click', function(){
                      var t = tb.getAttribute('data-theme');
                      if(t) document.body.setAttribute('data-theme', t);
                    });
                  });
                })();
                </script>