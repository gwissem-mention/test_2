import {GroupActionsHandler} from "./GroupActionsHandler/GroupActionsHandler";
import {ResetButtonHandler} from "./ResetButtonHandler/ResetButtonHandler";

document.addEventListener("DOMContentLoaded", () => {
    (new GroupActionsHandler()).bind();
    (new ResetButtonHandler()).bind();
});
