<?php 
 session_start();
 $connection = pg_connect(getenv("DATABASE_URL"));
 if (!$connection)
 { echo("error connecting..."); }
 include('checkAccess.php');
 include('navBar.php')
?>

</br>

<?php
if(isset($_SESSION["userid"]))
{
    
    $userID = $_SESSION["userid"];

    $teacherClassesPrepStmt = pg_prepare($connection, "teacherClassesQuery", "SELECT course_id, course_name FROM course where course_teacher = $1");
    $teacherClassesPrepStmt = pg_execute($connection, "teacherClassesQuery", array($userID));
    $teacherClassID = '';
    $teacherClassName = '';
    $teacherClassesArray = array();
    while($teacherClass= pg_fetch_row($teacherClassesPrepStmt))
    {
        $teacherClassID = $teacherClass[0]; //course id
        $teacherClassName = $teacherClass[1]; //course name
        $teacherClassesArray[$teacherClassID] = $teacherClassName;    
    }
    
    echo('<form name="selectClass" id="submitClassForm" method="get" onchange="submitClassChoice()">
    <div class="mb-3 w-50">
    <select class="form-select" aria-label="selectClass" name="selectClass" id="selectClass">
    <option>Select a Class</option>
    <script type="text/javascript">
    function submitClassChoice()
    {
         document.getElementById("submitClassForm").submit();
     }
    </script>');

    foreach ($teacherClassesArray as $key => $value) {
    //key is the course id, value is the course name, which is in the dropdown       
        echo('<option
        id="' . "$key" . '"
        name="' . "$value" . '"
        value="' . "$key" . '"
        >
        ' . "$value" .'
        </option>
        ');
        }

    echo
    ('</select>  
        </div>
    </form>
    ');







if(isset($_GET["selectClass"]))
{
    $classSelected = $_GET["selectClass"];
    echo("Now select a deck for this class");
}
; //end of selecting class



//now show all the flashcards for the selected deckname
echo('<form name="selectDeck" class="mx-auto" id="submitDeckForm" method="get" onchange="submitDeckChoice()">
<div class="mb-3 w-50">
<select class="form-select" aria-label="selectDeck" name="selectDeck" id="selectDeck">
<option>Select a Deck</option>
<script type="text/javascript">
function submitDeckChoice()
{
     document.getElementById("submitDeckForm").submit();
 }
</script>
');

$decknamesPrepStmt = pg_prepare($connection, "decknamesQuery", "SELECT DISTINCT flashcard_deckname FROM flashcards WHERE course_id = $1");
$decknamesPrepStmt = pg_execute($connection, "decknamesQuery", array($classSelected));
$deckSelected = '';

while($deck= pg_fetch_row($decknamesPrepStmt)){
    $deckname = $deck[0];
    echo('
    <option 
    
     id="' . "$deckname" . '"
     name="' . "$deckname" . '"
     value="' . "$deckname" . '">
     '. "$deckname" .'
     </option>');
}

echo
('</select>
</div>
</form>

');

if(isset($_GET["selectDeck"]))
        {
            $deckSelected = $_GET["selectDeck"];
        }

        $cardID = '';
        $cardDef = '';
        $cardTerm = '';
        $cardDifficulty = '';
        $setCardClass = '';

        //create an assoc. array of cardID's and cardDefinitions and save it into a variable
        //to retrieve the right definition, use the id of the button to look up the corresponding value
        //this value will replace the card term 
        $cardDefinitionsArray = array($cardID => $cardDef);
        $cardTermsArray = array($cardID => $cardTerm);

        $cardsForSelectedClassPrepStmt = pg_prepare($connection, "cardsForDeckQuery", "SELECT flashcard_id, flashcard_term, flashcard_definition, flashcard_difficulty FROM flashcards WHERE flashcard_deckname=$1");
        $cardsForSelectedClassPrepStmt = pg_execute($connection, "cardsForDeckQuery", array($deckSelected));

        while($card = pg_fetch_array($cardsForSelectedClassPrepStmt))
        {
            $cardID = $card["flashcard_id"];// . "card";
            $cardDef = $card["flashcard_definition"];
            $cardTerm = $card["flashcard_term"];
            $cardDifficulty = $card["flashcard_difficulty"];
            $cardDefinitionsArray[$cardID] = $cardDef;
            $cardTermsArray[$cardID] = $cardTerm;
            

            
            echo
            ('
            <form name="deckCards" id="submitDeckCards" class="mx-auto " method="GET">
            <div class="myCard" w-50>
                <div class="card w-25 text-center border border-primary border border-2 rounded mt-2">
                    <div class="' . "$setCardClass" . '">
                        <div class="text-primary">
                        <p>Term:  '. $card["flashcard_definition"] .'</p>
                        <p>Definition:  '. $card["flashcard_term"] .'</p>
                        <p>Difficulty:  '. $card["flashcard_difficulty"] .'</p>
                        </div>
                    </div>
                <div>
           
                <div class="card-footer">
                    <a data-toggle="modal" href="teacherViewEditCards.php?cardToEditID=' . "$cardID" .' ">Edit</a> 
                    <a  href="teacherViewEditCards.php?cardToDeleteID=' . "$cardID" .' ">Delete</a>   
                     
                    </div>
                </div>
            </div> 
               </form>
            ');                    
            }//end of while loop 

            if (isset($_GET["cardToDeleteID"])) {
                $cardToDeleteID = $_GET["cardToDeleteID"];
                $deletePrepStmt = pg_prepare($connection, "deleteCardQuery", "DELETE FROM flashcards WHERE flashcard_id= $1");
                $deletePrepStmt = pg_execute($connection, "deleteCardQuery", array($cardToDeleteID));
                
                if (  (pg_affected_rows($deletePrepStmt)) == 1 ) 
                {
                    echo('
                    <script>
                    alert("Successfully Deleted");
                    </script>
                    '); 
                }
                else
                {
                    echo('
                    <script>
                    alert("Issues Deleting, please try again!");
                    </script>
                    ');
                }
            }//

            if (isset($_GET["cardToEditID"])) {
                $cardToEditID = $_GET["cardToEditID"];

                $cardDef = '';
                $cardTerm = '';
                $cardDifficulty = '';

                $cardsToEditPrepStmt =  pg_prepare($connection, "editCardQuery", "SELECT flashcard_id, flashcard_term, flashcard_definition, flashcard_difficulty FROM flashcards WHERE flashcard_id=$1");
                $cardsToEditPrepStmt = pg_execute($connection, "editCardQuery", array($cardToEditID));
                
                while ($card = pg_fetch_array($cardsToEditPrepStmt)) 
                {
                    $cardDef = $card["flashcard_definition"];
                    $cardTerm = $card["flashcard_term"];
                    $cardDifficulty = $card["flashcard_difficulty"];  
                
                echo('
                <form name="editCardForm" id="editCard" class="mx-auto" method="POST">
                <div id="wholeCard" class="was-validated mx-auto">
                    <div class="card border-success mb-2 bg-light ">
                    <div class="card-header text-success mx-auto">
                    Editing Mode
                    </div>
                    <div class="row">
                    <div class="col m-2">
                        <div class="form-floating mb-2">
                        <input type="text" required class="form-control" id="cardTerm" name= "cardTerm" placeholder="Flashcard Term" value="' . "$cardTerm" . '">
                        <label for="cardTerm">Term</label>
                        </div>
                    </div>
                    <div class="col m-2">
                        <div class="form-floating mb-2">
                        <input type= "text" required class="form-control" name= "cardDef" placeholder="Flashcard Definition" id="cardDef" value="' . "$cardDef" . '">
                        <label for="cardDef">Definition</label>  
                        </div>
                    </div>');

                    if($cardDifficulty == 'easy')
                    {
                        echo('<div class="col m-2">
                        <select class="form-select" aria-label="selectDifficultyLevel" required name="cardDifficulty" onload="setSelectedValue()" value="' . "$cardDifficulty" . '">
                        <option selected value="easy">Easy</option>
                        <option value="medium">Medium</option>
                        <option value="Hard">Hard</option>
                        </select>
                    </div>');
                    }
                    else if($cardDifficulty == 'medium')
                    {
                        echo('<div class="col m-2">
                        <select class="form-select" aria-label="selectDifficultyLevel" required name="cardDifficulty" onload="setSelectedValue()" value="' . "$cardDifficulty" . '">
                        <option value="easy">Easy</option>
                        <option selected value="medium">Medium</option>
                        <option value="Hard">Hard</option>
                        </select>
                    </div>');
                    }
                    else
                    {
                        echo('<div class="col m-2">
                        <select class="form-select" aria-label="selectDifficultyLevel" required name="cardDifficulty" onload="setSelectedValue()" value="' . "$cardDifficulty" . '">
                        <option value="easy">Easy</option>
                        <option value="medium">Medium</option>
                        <option selected value="Hard">Hard</option>
                        </select>
                    </div>');
                    }
                    echo('</div>
                    <button type="submit" class="btn btn-success mx-auto w-25 mb-2" name="editSubmit">Submit my Changes</button>
                     
                </div>
                
                    </div>
           </div>
              
        </form>
           ');
                
           if(isset($_POST["editSubmit"]))
           {
            $iDofEdit =  $cardToEditID;
            $newCardDef = $_POST["cardDef"];
            $newCardTerm = $_POST["cardTerm"];
            $newCardDif = $_POST["cardDifficulty"];
            $cardsToEditPrepStmt = pg_prepare($connection, "submitEditCardQuery", "UPDATE flashcards
            SET flashcard_term= $1, flashcard_definition= $2, flashcard_difficulty=$3
            WHERE flashcard_id = $4");
             $cardsToEditPrepStmt = pg_execute($connection, "submitEditCardQuery", array($newCardTerm, $newCardDef, $newCardDif, $iDofEdit));
            
            if (  (pg_affected_rows($cardsToEditPrepStmt)) == 1 ) 
            {
                echo('
                <script>
                alert("Successfully edited");
                </script>
                '); 
            }
            else
            {
                echo('
                <script>
                alert("Issues editing, please try again!");
                </script>
                ');
            }
           }
            }//end of while loop for editing card

    } //if edit for a card is selcted
           
} //if userid is set
?>

