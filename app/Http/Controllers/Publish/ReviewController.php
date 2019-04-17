<?php
namespace App\Http\Controllers\Publish;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Models\Dataset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Display a listing of released and accepted datasets.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() : View
    {
        $user = Auth::user();
        $userId = $user->id;

        $builder = Dataset::query();
        //"select * from [documents] where [server_state] in (?) or ([server_state] = ? and [editor_id] = ?)"
        $datasets = $builder
        ->where('server_state', 'approved')
        ->where('reviewer_id', $userId)
        ->get();
        return view('workflow.review.index', [
            'datasets' => $datasets
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function review($id): View
    {
        $dataset = Dataset::with('user:id,login')->findOrFail($id);
        $dataset->fetchValues();
        $fieldnames = $dataset->describe();
        $fields = [];
        foreach ($fieldnames as $fieldName) {
            $field = $dataset->getField($fieldName);
            $modelClass = $field->getValueModelClass();
            $fieldValues = $field->getValue($fieldName);
            $value = "";

            if (null === $modelClass) {
                 $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', "\xEF\xBF\xBD ", $fieldValues);
            } else {
                // $fieldName = $field->getName();
    
    
                if (!is_array($fieldValues)) {
                    $fieldValues = array($fieldValues);
                }
                    
                foreach ($fieldValues as $fieldValue) {
                    // if a field has no value then is nothing more to do
                    // TODO maybe must be there an other solution
                    // FIXME remove code duplication (duplicates Opus_Model_Xml_Version*)
                    if (is_null($fieldValue)) {
                        continue;
                    }

                    if ($fieldValue instanceof \Illuminate\Database\Eloquent\Model) {
                        //$this->_mapModelAttributes($value, $dom, $childNode);
                        $attributes = array_keys($fieldValue->getAttributes());
                        foreach ($attributes as $property_name) {
                            $fieldName = self::convertColumnToFieldname($property_name);
                            // $field = new Field($fieldName);
                            $fieldval = $fieldValue->{$property_name};
                            $value = $value . $fieldName . ": "  . $fieldval . "; ";
                        }
                    } elseif ($fieldValue instanceof \Carbon\Carbon) {
                        $value = $value . " Year " . $fieldValue->year;
                        $value = $value . " Month " . $fieldValue->month;
                        $value = $value . " Day " . $fieldValue->day;
                        $value = $value . " Hour " . $fieldValue->hour;
                        $value = $value . " Minute " . $fieldValue->minute;
                        $value = $value . " Second " . $fieldValue->second;
                        $value = $value . " UnixTimestamp " . $fieldValue->timestamp;
                        $value = $value . " Timezone " . $fieldValue->tzName;
                    } elseif (is_array($fieldValue)) {
                        $attributes = $fieldValue;
                        $value = "<ul>";
                        foreach ($attributes as $property_name => $subValue) {
                            $value = $value . "<li>" . $property_name . " : " .  $subValue . "</li>";
                        }
                        $value = $value . "</ul>";
                    }
                }
            }
            if ($value != "") {
                $fields[$fieldName] = $value;
            }
        }

        return view('workflow.review.review', [
            'dataset' => $dataset,
            'fields' => $fields
        ]);
    }

    
    public function reviewUpdate(Request $request, $id)
    {
        $dataset = Dataset::findOrFail($id);
        $input = $request->all();
        $input['server_state'] = 'reviewed';

        if ($dataset->update($input)) {
            // event(new PageUpdated($page));
            return redirect()
                ->route('publish.workflow.review.index')
                ->with('flash_message', 'You have successfully reviewed one dataset!');
        }
        throw new GeneralException(trans('exceptions.publish.review.update_error'));
    }

    //snakeToCamel
    private static function convertColumnToFieldname($columnname)
    {
        //return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $columnname))));
        return ucwords(str_replace(['-', '_'], ' ', $columnname));
    }
}
