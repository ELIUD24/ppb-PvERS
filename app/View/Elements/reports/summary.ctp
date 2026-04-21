<div class="row-fluid">
    <div class="span2">
        <ul class="nav nav-list sidebar-manager">
            <li class="text-center ">
                <a href="/reports" class="text-success">REPORTS</a>
            </li>
            <li class="divider"></li>
            <li class="nav-header"><i class="fa fa-ambulance" aria-hidden="true"></i> SUSPECTED ADVERSE DRUG REACTIONS</li>
            <li class="active">
                <a href="/reports/summary"><i class="fa fa-caret-right" aria-hidden="true"></i> SADR</a>
            </li>
            <li class="nav-header"><i class="fa fa-child" aria-hidden="true"></i> ADVERSE EVENT FOLLOWING IMMUNIZATION</li>
            <li class="">
                <a href="/reports/aefi_summary"><i class="fa fa-caret-right" aria-hidden="true"></i> AEFI</a>
            </li>
            <li class="nav-header"><i class="fa fa-medkit" aria-hidden="true"></i>  POOR QUALITY HEALTH PRODUCTS AND TECHNOLOGIES</li>
            <li class="">
                <a href="/reports/pqmps_summary"><i class="fa fa-caret-right" aria-hidden="true"></i> PQHPTs</a>
            </li>
            <li class="nav-header"><i class="fa fa-stethoscope" aria-hidden="true"></i> Medical Devices</li>
            <li class="">
                <a href="/reports/devices_summary"><i class="fa fa-caret-right" aria-hidden="true"></i> Medical devices</a>
            </li>
            <li class="nav-header"><i class="fa fa-chain-broken" aria-hidden="true"></i> Medication Errors</li>
            <li class="">
                <a href="/reports/medications_summary"><i class="fa fa-caret-right" aria-hidden="true"></i> Medications</a>
            </li>
            <li class="nav-header"><i class="fa fa-eyedropper" aria-hidden="true"></i> Blood Transfusion</li>
            <li class="">
                <a href="/reports/transfusions_summary"><i class="fa fa-caret-right" aria-hidden="true"></i> Transfusion</a>
            </li>
            <li class="nav-header"><i class="fa fa-thermometer-full" aria-hidden="true"></i> E2B</li>
            <li class="">
                <a href="/reports/e2b_summary"><i class="fa fa-caret-right" aria-hidden="true"></i> E2B</a>
            </li>
            <li class="divider"></li>
        </ul>
    </div>

    <div class="span10">
        <form action="/reports/summary" class="ctr-groups" style="padding:9px; background-color: #F5F5F5" id="ReportSummaryForm" method="post" accept-charset="utf-8">
            <div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>
            <table class="table table-condensed" style="margin-bottom: 2px;">
                <tbody>
                    <tr>
                        <td>
                            <label for="ReportStartDate" class="required">Report Dates</label>
                            <input name="data[Report][start_date]" class="span3 input-small unauthorized_index" placeholder="Start Date" type="text" id="ReportStartDate"/>
                            -to-
                            <input name="data[Report][end_date]" class="span3 input-small unauthorized_index" placeholder="End Date" type="text" id="ReportEndDate"/>
                            <a style="font-weight:normal" onclick="$('.unauthorized_index').val('');">
                                <em class="accordion-toggle">clear!</em>
                            </a>
                        </td>
                        <td>
                            <label for="ReportCountyId" class="required">County</label>
                            <select name="data[Report][county_id]" class="span11 unauthorized_index" placeholder="All" id="ReportCountyId">
                                <option value="">All</option>
                                <option value="30">Baringo</option>
                                <option value="36">Bomet</option>
                                <option value="39">Bungoma</option>
                                <option value="40">Busia</option>
                                <option value="28">Elgeyo/Marakwet</option>
                                <option value="14">Embu</option>
                                <option value="7">Garissa</option>
                                <option value="43">Homa Bay</option>
                                <option value="11">Isiolo</option>
                                <option value="34">Kajiado</option>
                                <option value="37">Kakamega</option>
                                <option value="35">Kericho</option>
                                <option value="22">Kiambu</option>
                                <option value="3">Kilifi</option>
                                <option value="20">Kirinyaga</option>
                                <option value="45">Kisii</option>
                                <option value="42">Kisumu</option>
                                <option value="15">Kitui</option>
                                <option value="2">Kwale</option>
                                <option value="31">Laikipia</option>
                                <option value="5">Lamu</option>
                                <option value="16">Machakos</option>
                                <option value="17">Makueni</option>
                                <option value="9">Mandera</option>
                                <option value="10">Marsabit</option>
                                <option value="12">Meru</option>
                                <option value="44">Migori</option>
                                <option value="1">Mombasa</option>
                                <option value="21">Murang'a</option>
                                <option value="47">Nairobi County</option>
                                <option value="32">Nakuru</option>
                                <option value="29">Nandi</option>
                                <option value="33">Narok</option>
                                <option value="46">Nyamira</option>
                                <option value="18">Nyandarua</option>
                                <option value="19">Nyeri</option>
                                <option value="25">Samburu</option>
                                <option value="41">Siaya</option>
                                <option value="6">Taita Taveta</option>
                                <option value="4">Tana River</option>
                                <option value="13">Tharaka Nithi</option>
                                <option value="26">Trans Nzoia</option>
                                <option value="23">Turkana</option>
                                <option value="27">Uasin Gishu</option>
                                <option value="38">Vihiga</option>
                                <option value="8">Wajir</option>
                                <option value="24">West Pokot</option>
                            </select>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <label for="ReportSuspectedDrug" class="required">Suspected drug </label>
                            <input name="data[Report][suspected_drug]" class="span8 unauthorized_index" placeholder="drug name" type="text" id="ReportSuspectedDrug"/>
                        </td>
                        <td>
                            <label for="ReportAgeGroup" class="required">Age Group</label>
                            <select name="data[Report][age_group]" class="span11 unauthorized_index" id="ReportAgeGroup">
                                <option value="">All</option>
                                <option value="neonate">neonate [0-1 month]</option>
                                <option value="infant">infant [1 month-1 year]</option>
                                <option value="child">child [1 year - 11 years]</option>
                                <option value="adolescent">adolescent [12-17 years]</option>
                                <option value="adult">adult [18-64 years]</option>
                                <option value="elderly">elderly [&gt;65 years]</option>
                            </select>
                        </td>
                        <td>
                            <label for="ReportGender" class="required">Gender</label>
                            <select name="data[Report][gender]" class="span11 unauthorized_index" placeholder="All" id="ReportGender">
                                <option value="">All</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="align-content: flex-end;">
                            <button class="btn btn-primary" formnovalidate="formnovalidate" style="margin-bottom: 5px" type="submit"><i class="icon-search icon-white"></i> Search</button>
                        </td>
                        <td>
                            <a href="/reports" class="btn" style="margin-bottom: 5px"><i class="icon-remove"></i> Clear</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
        <hr>

        <div class="row-fluid">
            <div class="span6">
                <h4>Geographical Distribution</h4>
                <div class="tab">
                    <button class="tablinks" onclick="geoTab(event, 'geoChart')" id="geoOpen">
                        <i class="fa fa-pie-chart"></i> Chart
                    </button>
                    <button class="tablinksgeo" onclick="geoTab(event, 'geoTable')">
                        <i class="fa fa-table"></i> Table
                    </button>
                </div>

                <div id="geoChart" class="tabcontentgeo">
                    <div id="sadrs-geo"></div>
                </div>

                <div id="geoTable" class="tabcontentgeo">
                    <table class="table table-condensed table-bordered" id="datatablegeo">
                        <thead>
                            <tr>
                                <th>County</th>
                                <th>SADRs</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><th>Baringo</th><td>29</td></tr>
                            <tr><th>Bomet</th><td>7</td></tr>
                            <tr><th>Bungoma</th><td>215</td></tr>
                            <tr><th>Busia</th><td>28</td></tr>
                            <tr><th>Elgeyo/Marakwet</th><td>6</td></tr>
                            <tr><th>Embu</th><td>81</td></tr>
                            <tr><th>Garissa</th><td>12</td></tr>
                            <tr><th>Homa Bay</th><td>121</td></tr>
                            <tr><th>Isiolo</th><td>15</td></tr>
                            <tr><th>Kajiado</th><td>44</td></tr>
                            <tr><th>Kakamega</th><td>221</td></tr>
                            <tr><th>Kericho</th><td>38</td></tr>
                            <tr><th>Kiambu</th><td>348</td></tr>
                            <tr><th>Kilifi</th><td>137</td></tr>
                            <tr><th>Kirinyaga</th><td>553</td></tr>
                            <tr><th>Kisii</th><td>178</td></tr>
                            <tr><th>Kisumu</th><td>196</td></tr>
                            <tr><th>Kitui</th><td>84</td></tr>
                            <tr><th>Kwale</th><td>12</td></tr>
                            <tr><th>Laikipia</th><td>76</td></tr>
                            <tr><th>Lamu</th><td>2</td></tr>
                            <tr><th>Machakos</th><td>112</td></tr>
                            <tr><th>Makueni</th><td>134</td></tr>
                            <tr><th>Mandera</th><td>3</td></tr>
                            <tr><th>Marsabit</th><td>3</td></tr>
                            <tr><th>Meru</th><td>146</td></tr>
                            <tr><th>Migori</th><td>155</td></tr>
                            <tr><th>Mombasa</th><td>485</td></tr>
                            <tr><th>Murang'a</th><td>97</td></tr>
                            <tr><th>Nairobi County</th><td>1299</td></tr>
                            <tr><th>Nakuru</th><td>69</td></tr>
                            <tr><th>Nandi</th><td>34</td></tr>
                            <tr><th>Narok</th><td>20</td></tr>
                            <tr><th>Nyamira</th><td>32</td></tr>
                            <tr><th>Nyandarua</th><td>27</td></tr>
                            <tr><th>Nyeri</th><td>127</td></tr>
                            <tr><th>Samburu</th><td>188</td></tr>
                            <tr><th>Siaya</th><td>294</td></tr>
                            <tr><th>Taita Taveta</th><td>39</td></tr>
                            <tr><th>Tana River</th><td>4</td></tr>
                            <tr><th>Tharaka Nithi</th><td>58</td></tr>
                            <tr><th>Trans Nzoia</th><td>23</td></tr>
                            <tr><th>Turkana</th><td>73</td></tr>
                            <tr><th>Uasin Gishu</th><td>64</td></tr>
                            <tr><th>Vihiga</th><td>53</td></tr>
                            <tr><th>West Pokot</th><td>18</td></tr>
                        </tbody>
                    </table>
                    <table class="table table-condensed table-bordered">
                        <tbody>
                            <tr>
                                <th>Total</th>
                                <th>5960</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="span6">
                <h4>Gender Distribution</h4>
                <div class="tab">
                    <button class="tablinks" onclick="sexTab(event, 'sexChart')" id="sexOpen">
                        <i class="fa fa-pie-chart"></i> Chart
                    </button>
                    <button class="tablinkssex" onclick="sexTab(event, 'sexTable')">
                        <i class="fa fa-table"></i> Table
                    </button>
                </div>

                <div id="sexChart" class="tabcontentsex">
                    <div id="sadrs-sex"></div>
                </div>

                <div id="sexTable" class="tabcontentsex">
                    <table class="table table-condensed table-bordered" id="datatablesex">
                        <thead>
                            <tr>
                                <th>Sex</th>
                                <th>ADRs</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><th></th><td>1</td></tr>
                            <tr><th>Female</th><td>3593</td></tr>
                            <tr><th>Male</th><td>2324</td></tr>
                            <tr><th>Unknown</th><td>42</td></tr>
                        </tbody>
                    </table>
                    <table class="table table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th>Total</th>
                                <th>5960</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <hr>
        <div class="row-fluid">
            <div class="span6">
                <h4>Age Distribution</h4>
                <div class="tab">
                    <button class="tablinks" onclick="ageTab(event, 'ageChart')" id="ageOpen">
                        <i class="fa fa-pie-chart"></i> Chart
                    </button>
                    <button class="tablinksage" onclick="ageTab(event, 'ageTable')">
                        <i class="fa fa-table"></i> Table
                    </button>
                </div>

                <div id="ageChart" class="tabcontentage">
                    <div id="sadrs-age"></div>
                </div>

                <div id="ageTable" class="tabcontentage">
                    <table class="table table-condensed table-bordered" id="datatableage">
                        <thead>
                            <tr>
                                <th>Age group</th>
                                <th>SADRs</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><th>adolescent</th><td>179</td></tr>
                            <tr><th>adult</th><td>4672</td></tr>
                            <tr><th>child</th><td>361</td></tr>
                            <tr><th>elderly</th><td>629</td></tr>
                            <tr><th>infant</th><td>73</td></tr>
                            <tr><th>neonate</th><td>41</td></tr>
                            <tr><th>unknown</th><td>5</td></tr>
                        </tbody>
                    </table>
                    <table class="table table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th>Total</th>
                                <th>5960</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="span6">
                <h4>SADRs Per Year</h4>
                <div class="tab">
                    <button class="tablinks" onclick="yearTab(event, 'yearChart')" id="yearOpen">
                        <i class="fa fa-pie-chart"></i> Chart
                    </button>
                    <button class="tablinksyear" onclick="yearTab(event, 'yearTable')">
                        <i class="fa fa-table"></i> Table
                    </button>
                </div>

                <div id="yearChart" class="tabcontentyear">
                    <div id="sadrs-year"></div>
                </div>

                <div id="yearTable" class="tabcontentyear">
                    <table class="table table-condensed table-bordered" id="datatableyear">
                        <thead>
                            <tr>
                                <th>Year</th>
                                <th>SADRs</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><th>2021</th><td>611</td></tr>
                            <tr><th>2022</th><td>1154</td></tr>
                            <tr><th>2023</th><td>1312</td></tr>
                            <tr><th>2024</th><td>1268</td></tr>
                            <tr><th>2025</th><td>1146</td></tr>
                            <tr><th>2026</th><td>469</td></tr>
                        </tbody>
                    </table>
                    <table class="table table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th>Total</th>
                                <th>5960</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <hr>
        <div class="row-fluid">
            <div class="span12">
                <h4>SADRs per Month</h4>
                <div class="tab">
                    <button class="tablinks" onclick="monthTab(event, 'monthChart')" id="monthOpen">
                        <i class="fa fa-pie-chart"></i> Chart
                    </button>
                    <button class="tablinksmonth" onclick="monthTab(event, 'monthTable')">
                        <i class="fa fa-table"></i> Table
                    </button>
                </div>

                <div id="monthChart" class="tabcontentmonth">
                    <div id="sadrs-month"></div>
                </div>

                <div id="monthTable" class="tabcontentmonth">
                    <table class="table table-condensed table-bordered" id="datatablemonth">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>SADRs</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><th>Jan 2024</th><td>61</td></tr>
                            <tr><th>Jan 2023</th><td>97</td></tr>
                            <tr><th>Jan 2026</th><td>98</td></tr>
                            <tr><th>Jan 2022</th><td>54</td></tr>
                            <tr><th>Jan 2025</th><td>96</td></tr>
                            <tr><th>Feb 2023</th><td>121</td></tr>
                            <tr><th>Feb 2024</th><td>167</td></tr>
                            <tr><th>Feb 2026</th><td>138</td></tr>
                            <tr><th>Feb 2022</th><td>65</td></tr>
                            <tr><th>Feb 2025</th><td>33</td></tr>
                            <tr><th>Mar 2021</th><td>25</td></tr>
                            <tr><th>Mar 2025</th><td>90</td></tr>
                            <tr><th>Mar 2022</th><td>117</td></tr>
                            <tr><th>Mar 2023</th><td>108</td></tr>
                            <tr><th>Mar 2024</th><td>91</td></tr>
                            <tr><th>Mar 2026</th><td>119</td></tr>
                            <tr><th>Apr 2023</th><td>109</td></tr>
                            <tr><th>Apr 2026</th><td>114</td></tr>
                            <tr><th>Apr 2021</th><td>74</td></tr>
                            <tr><th>Apr 2025</th><td>65</td></tr>
                            <tr><th>Apr 2022</th><td>78</td></tr>
                            <tr><th>Apr 2024</th><td>69</td></tr>
                            <tr><th>May 2025</th><td>103</td></tr>
                            <tr><th>May 2024</th><td>82</td></tr>
                            <tr><th>May 2021</th><td>27</td></tr>
                            <tr><th>May 2022</th><td>101</td></tr>
                            <tr><th>May 2023</th><td>89</td></tr>
                            <tr><th>Jun 2024</th><td>118</td></tr>
                            <tr><th>Jun 2021</th><td>72</td></tr>
                            <tr><th>Jun 2022</th><td>79</td></tr>
                            <tr><th>Jun 2023</th><td>132</td></tr>
                            <tr><th>Jun 2025</th><td>67</td></tr>
                            <tr><th>Jul 2022</th><td>82</td></tr>
                            <tr><th>Jul 2023</th><td>95</td></tr>
                            <tr><th>Jul 2021</th><td>66</td></tr>
                            <tr><th>Jul 2025</th><td>157</td></tr>
                            <tr><th>Jul 2024</th><td>127</td></tr>
                            <tr><th>Aug 2024</th><td>123</td></tr>
                            <tr><th>Aug 2023</th><td>158</td></tr>
                            <tr><th>Aug 2022</th><td>170</td></tr>
                            <tr><th>Aug 2021</th><td>80</td></tr>
                            <tr><th>Aug 2025</th><td>146</td></tr>
                            <tr><th>Sep 2021</th><td>69</td></tr>
                            <tr><th>Sep 2025</th><td>100</td></tr>
                            <tr><th>Sep 2022</th><td>108</td></tr>
                            <tr><th>Sep 2024</th><td>122</td></tr>
                            <tr><th>Sep 2023</th><td>75</td></tr>
                            <tr><th>Oct 2025</th><td>90</td></tr>
                            <tr><th>Oct 2021</th><td>43</td></tr>
                            <tr><th>Oct 2022</th><td>84</td></tr>
                            <tr><th>Oct 2024</th><td>112</td></tr>
                            <tr><th>Oct 2023</th><td>111</td></tr>
                            <tr><th>Nov 2023</th><td>122</td></tr>
                            <tr><th>Nov 2022</th><td>132</td></tr>
                            <tr><th>Nov 2021</th><td>104</td></tr>
                            <tr><th>Nov 2025</th><td>103</td></tr>
                            <tr><th>Nov 2024</th><td>101</td></tr>
                            <tr><th>Dec 2021</th><td>51</td></tr>
                            <tr><th>Dec 2025</th><td>96</td></tr>
                            <tr><th>Dec 2024</th><td>95</td></tr>
                            <tr><th>Dec 2023</th><td>95</td></tr>
                            <tr><th>Dec 2022</th><td>84</td></tr>
                        </tbody>
                    </table>
                    <table class="table table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th>Total</th>
                                <th>5960</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <hr>

        <script type="text/javascript">
            function geoTab(evt, geotabName) {
                var i, tabcontent, tablinks;
                tabcontent = document.getElementsByClassName("tabcontentgeo");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }
                tablinks = document.getElementsByClassName("tablinksgeo");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }
                document.getElementById(geotabName).style.display = "block";
                evt.currentTarget.className += " active";
            }

            function sexTab(evt, sextabName) {
                var i, tabcontent, tablinks;
                tabcontent = document.getElementsByClassName("tabcontentsex");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }
                tablinks = document.getElementsByClassName("tablinkssex");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }
                document.getElementById(sextabName).style.display = "block";
                evt.currentTarget.className += " active";
            }

            function ageTab(evt, agetabName) {
                var i, tabcontent, tablinks;
                tabcontent = document.getElementsByClassName("tabcontentage");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }
                tablinks = document.getElementsByClassName("tablinksage");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }
                document.getElementById(agetabName).style.display = "block";
                evt.currentTarget.className += " active";
            }

            function monthTab(evt, monthtabName) {
                var i, tabcontent, tablinks;
                tabcontent = document.getElementsByClassName("tabcontentmonth");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }
                tablinks = document.getElementsByClassName("tablinksmonth");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }
                document.getElementById(monthtabName).style.display = "block";
                evt.currentTarget.className += " active";
            }

            function yearTab(evt, yeartabName) {
                var i, tabcontent, tablinks;
                tabcontent = document.getElementsByClassName("tabcontentyear");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }
                tablinks = document.getElementsByClassName("tablinksyear");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }
                document.getElementById(yeartabName).style.display = "block";
                evt.currentTarget.className += " active";
            }

            document.getElementById("geoOpen").click();
            document.getElementById("sexOpen").click();
            document.getElementById("ageOpen").click();
            document.getElementById("monthOpen").click();
            document.getElementById("yearOpen").click();

            Highcharts.chart('sadrs-geo', {
                data: { table: 'datatablegeo' },
                chart: { type: 'bar' },
                title: { text: '' },
                yAxis: { allowDecimals: false, title: { text: 'Units' } },
                tooltip: {
                    formatter: function() {
                        return '<b>' + this.series.name + '</b><br/>' + this.point.y + ' ' + this.point.name.toLowerCase();
                    }
                }
            });

            Highcharts.chart('sadrs-sex', {
                data: { table: 'datatablesex' },
                chart: { type: 'pie' },
                title: { text: '' },
                yAxis: { allowDecimals: false, title: { text: 'Units' } },
                plotOptions: {
                    pie: {
                        dataLabels: { enabled: true, format: '<b>{point.name}</b>: {point.percentage:.1f}%' }
                    }
                },
                tooltip: { pointFormat: '{series.name}: <b>{point.y}</b><br/>Percentage: <b>{point.percentage:.1f}%</b>' },
                series: [{
                    data: {
                        parsed: function(columns) {
                            var totalIndex = columns[0].indexOf('Total');
                            if (totalIndex !== -1) {
                                columns[0].splice(totalIndex, 1);
                                columns[1].splice(totalIndex, 1);
                            }
                        }
                    }
                }]
            });

            Highcharts.chart('sadrs-age', {
                data: { table: 'datatableage' },
                chart: { type: 'bar' },
                title: { text: '' },
                yAxis: { allowDecimals: false, title: { text: 'Units' } },
                tooltip: {
                    formatter: function() {
                        return '<b>' + this.series.name + '</b><br/>' + this.point.y + ' ' + this.point.name.toLowerCase();
                    }
                }
            });

            Highcharts.chart('sadrs-month', {
                data: { table: 'datatablemonth' },
                chart: { type: 'column' },
                title: { text: '' },
                yAxis: { allowDecimals: false, title: { text: 'Units' } },
                tooltip: {
                    formatter: function() {
                        return '<b>' + this.series.name + '</b><br/>' + this.point.y + ' ' + this.point.name.toLowerCase();
                    }
                }
            });

            Highcharts.chart('sadrs-year', {
                data: { table: 'datatableyear' },
                chart: { type: 'bar' },
                title: { text: '' },
                yAxis: { allowDecimals: false, title: { text: 'Units' } },
                tooltip: {
                    formatter: function() {
                        return '<b>' + this.series.name + '</b><br/>' + this.point.y + ' ' + this.point.name.toLowerCase();
                    }
                }
            });
        </script>

        <script type="text/javascript">
            $(function() {
                var adates = $('#ReportStartDate, #ReportEndDate').datepicker({
                    minDate: "-100Y",
                    maxDate: "-0D",
                    dateFormat: 'dd-mm-yy',
                    format: 'dd-mm-yyyy',
                    endDate: '-0d',
                    showButtonPanel: true,
                    changeMonth: true,
                    changeYear: true,
                    showAnim: 'show',
                    onSelect: function(selectedDate) {
                        var option = this.id == "ReportStartDate" ? "minDate" : "maxDate",
                            instance = $(this).data("datepicker"),
                            date = $.datepicker.parseDate(
                                instance.settings.dateFormat || $.datepicker._defaults.dateFormat,
                                selectedDate, instance.settings);
                        adates.not(this).datepicker("option", option, date);
                    }
                });
            });
        </script>
    </div>
</div>