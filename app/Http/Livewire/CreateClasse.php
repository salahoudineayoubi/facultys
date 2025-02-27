<?php


namespace App\Http\Livewire;

use App\Models\Classe;
use App\Models\Level;
use App\Models\SchoolYear;
use Exception;
use Livewire\Component;

class CreateClasse extends Component
{
    public $libelle;
    public $level_id;

    public function store(Classe $classe)
    {
        $this->validate([
            'libelle' => 'string|required',
            'level_id' => 'string|required',
        ]);

        try {
            $classe->libelle = $this->libelle;
            $classe->level_id = $this->level_id;
            $classe->save();

            return redirect()->route('classes')->with('success', 'Classe ajoutée');
        } catch (Exception $e) {
            // Sera pris en compte si on a un problème
        }
    }

    public function render()
    {
        // Récupérer l'année dont le active = '1'
        $activeSchoolYear = SchoolYear::where('active', '1')->first();

        // Vérifier si une année scolaire active a été trouvée
        if (!$activeSchoolYear) {
            // Gérer le cas où il n'y a pas d'année scolaire active
            $currentLevels = [];
        } else {
            // Charger les niveaux qui appartiennent à l'année en cours
            $currentLevels = Level::where('school_year_id', $activeSchoolYear->id)->get();
        }

        return view('livewire.create-classe', compact('currentLevels'));
    }
}
