# User registration

User registration can be done either from scratch or after receiving an invite email. The steps taken and information gathered depend on which path is taken. The chart below outlines the different flows.

```mermaid
flowchart TD
    Start --> user_invited{Was user invited?}
    user_invited --> invited_yes[Yes] --> details_invited[Enter details]
    user_invited --> invited_no[No] --> details_not_invited[Enter details]
    details_invited --> End
    details_not_invited --> subdomain[Enter subdomain details]
    subdomain --> setup_project{Setup project now?}
    setup_project --> setup_yes[Yes] --> project[Enter project details] --> End
    setup_project --> setup_no[No] --> End
```
