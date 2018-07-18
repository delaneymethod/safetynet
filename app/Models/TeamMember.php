<?php
/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

namespace App\Models;

use App\Models\{Status, Location};
use Spatie\EloquentSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\SortableTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamMember extends Model implements Sortable
{
	use SortableTrait;
	
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'team_members';
    
	protected $characterSet = 'UTF-8';
	
	protected $flags = ENT_QUOTES;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id',
		'full_name',
		'email',
		'job_title',
		'image',
		'bio',
		'locatin_id',
		'status_id',
		'order',
	];
	
	public $sortable = [
		'order_column_name' => 'order',
		'sort_when_creating' => true,
	];
	
	/**
	 * Get the status record associated with the team member.
	 */
	public function status() : BelongsTo
	{
		return $this->belongsTo(Status::class);
	}
	
	/**
	 * Get the location records associated with the team member.
	 */
	public function location() : BelongsTo
	{
		return $this->belongsTo(Location::class);
	}
	
	/**
	 * Checks if team member is active.
	 *
	 * @return bool
	 */
	public function isActive() : bool
	{
		return $this->status_id == 1;
	}
	
	/**
	 * Checks if team member is in active.
	 *
	 * @return bool
	 */
	public function isInactive() : bool
	{
		return $this->status_id == 2;
	}
}
