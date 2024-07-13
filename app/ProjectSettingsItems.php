<?php

namespace App;

enum ProjectSettingsItems :string
{
    case LEADS_MANAGEMENT_ENABLED = 'leads_management_enabled'; //if false, everythig related to leads will be disabled.
}
