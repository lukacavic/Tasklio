<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property-read \App\Models\Organisation|null $organisation
 * @property-read \App\Models\User|null $userCreated
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel withoutTrashed()
 */
	class BaseModel extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $phone
 * @property string|null $website
 * @property string|null $address
 * @property string|null $city
 * @property string|null $zip_code
 * @property string|null $country
 * @property int $organisation_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Contact> $contacts
 * @property-read int|null $contacts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Document> $documents
 * @property-read int|null $documents_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Note> $notes
 * @property-read int|null $notes_count
 * @property-read \App\Models\Organisation|null $organisation
 * @property-read \App\Models\Contact|null $primaryContact
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Project> $projects
 * @property-read int|null $projects_count
 * @property-read \App\Models\User|null $userCreated
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Vault> $vaults
 * @property-read int|null $vaults_count
 * @method static \Illuminate\Database\Eloquent\Builder|Client newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Client newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Client onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Client query()
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereOrganisationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereZipCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Client withoutTrashed()
 */
	class Client extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $client_id
 * @property string $full_name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $position
 * @property int $organisation_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Organisation|null $organisation
 * @property-read \App\Models\User|null $userCreated
 * @method static \Illuminate\Database\Eloquent\Builder|Contact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact query()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereOrganisationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact withoutTrashed()
 */
	class Contact extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $title
 * @property int $user_id
 * @property string|null $related_type
 * @property int|null $related_id
 * @property int|null $project_id
 * @property int $organisation_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Organisation|null $organisation
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $project
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $related
 * @property \Illuminate\Database\Eloquent\Collection<int, \Spatie\Tags\Tag> $tags
 * @property-read int|null $tags_count
 * @property-read \App\Models\User|null $user
 * @property-read \App\Models\User|null $userCreated
 * @method static \Illuminate\Database\Eloquent\Builder|Document newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Document newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Document onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Document query()
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereOrganisationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereRelatedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereRelatedType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Document withAllTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder|Document withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Document withAnyTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder|Document withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Document withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Document withoutTrashed()
 */
	class Document extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $team_id
 * @property string|null $from
 * @property string|null $to
 * @property string|null $cc
 * @property string|null $bcc
 * @property string|null $subject
 * @property string|null $text_body
 * @property string|null $html_body
 * @property string|null $raw_body
 * @property string|null $sent_debug_info
 * @property array|null $attachments
 * @property int|null $organisation_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Organisation|null $organisation
 * @method static \Illuminate\Database\Eloquent\Builder|Email filter(array $filters)
 * @method static \Illuminate\Database\Eloquent\Builder|Email newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Email newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Email query()
 * @method static \Illuminate\Database\Eloquent\Builder|Email whereAttachments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Email whereBcc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Email whereCc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Email whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Email whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Email whereHtmlBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Email whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Email whereOrganisationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Email whereRawBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Email whereSentDebugInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Email whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Email whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Email whereTextBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Email whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Email whereUpdatedAt($value)
 */
	class Email extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon $start_at
 * @property \Illuminate\Support\Carbon $end_at
 * @property string $title
 * @property string|null $description
 * @property int $user_id
 * @property int|null $project_id
 * @property string|null $color
 * @property int $organisation_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Organisation|null $organisation
 * @property-read \App\Models\Project|null $project
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $related
 * @property \Illuminate\Database\Eloquent\Collection<int, \Spatie\Tags\Tag> $tags
 * @property-read int|null $tags_count
 * @property-read \App\Models\User|null $userCreated
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Event query()
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereOrganisationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Event withAllTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder|Event withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Event withAnyTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder|Event withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Event withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Event withoutTrashed()
 */
	class Event extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property int $user_id
 * @property int $category_id
 * @property int|null $project_id
 * @property int $organisation_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\KnowledgeCategory|null $category
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Organisation|null $organisation
 * @property-read \App\Models\Project|null $project
 * @property-read \App\Models\User|null $userCreated
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeArticle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeArticle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeArticle onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeArticle query()
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeArticle whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeArticle whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeArticle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeArticle whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeArticle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeArticle whereOrganisationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeArticle whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeArticle whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeArticle whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeArticle whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeArticle withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeArticle withoutTrashed()
 */
	class KnowledgeArticle extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $title
 * @property int $user_id
 * @property int|null $project_id
 * @property string|null $color
 * @property string|null $short_description
 * @property int $organisation_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KnowledgeArticle> $articles
 * @property-read int|null $articles_count
 * @property-read \App\Models\Organisation|null $organisation
 * @property-read \App\Models\Project|null $project
 * @property-read \App\Models\User|null $userCreated
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeCategory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeCategory whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeCategory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeCategory whereOrganisationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeCategory whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeCategory whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeCategory whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeCategory whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeCategory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeCategory withoutTrashed()
 */
	class KnowledgeCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $first_name
 * @property string|null $last_name
 * @property string|null $position
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $mobile
 * @property int $lost
 * @property string $company
 * @property int|null $client_id
 * @property string|null $client_converted_at
 * @property int|null $project_id
 * @property int|null $status_id
 * @property int|null $source_id
 * @property string|null $country
 * @property string|null $website
 * @property int|null $assigned_user_id
 * @property string|null $description
 * @property int $important
 * @property \Illuminate\Support\Carbon|null $last_contact_at
 * @property int $organisation_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User|null $assignedUser
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Document> $documents
 * @property-read int|null $documents_count
 * @property-read string $full_name
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Note> $notes
 * @property-read int|null $notes_count
 * @property-read \App\Models\Organisation|null $organisation
 * @property-read \App\Models\Project|null $project
 * @property \Illuminate\Database\Eloquent\Collection<int, \Spatie\Tags\Tag> $tags
 * @property-read \App\Models\LeadSource|null $source
 * @property-read \App\Models\LeadStatus|null $status
 * @property-read int|null $tags_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Task> $tasks
 * @property-read int|null $tasks_count
 * @property-read \App\Models\User|null $userCreated
 * @method static \Illuminate\Database\Eloquent\Builder|Lead newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lead newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lead onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Lead query()
 * @method static \Illuminate\Database\Eloquent\Builder|Lead whereAssignedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lead whereClientConvertedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lead whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lead whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lead whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lead whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lead whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lead whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lead whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lead whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lead whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lead whereImportant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lead whereLastContactAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lead whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lead whereLost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lead whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lead whereOrganisationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lead wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lead wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lead whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lead whereSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lead whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lead whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lead whereWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lead withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Lead withAllTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder|Lead withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Lead withAnyTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder|Lead withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Lead withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Lead withoutTrashed()
 */
	class Lead extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property int $project_id
 * @property int $organisation_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Organisation|null $organisation
 * @property-read \App\Models\Project|null $project
 * @property-read \App\Models\User|null $userCreated
 * @method static \Illuminate\Database\Eloquent\Builder|LeadSource newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeadSource newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeadSource onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|LeadSource query()
 * @method static \Illuminate\Database\Eloquent\Builder|LeadSource whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadSource whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadSource whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadSource whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadSource whereOrganisationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadSource whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadSource whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadSource withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|LeadSource withoutTrashed()
 */
	class LeadSource extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $color
 * @property int $sort_order
 * @property int $is_client
 * @property int $is_created
 * @property int|null $project_id
 * @property int $organisation_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lead> $leads
 * @property-read int|null $leads_count
 * @property-read \App\Models\Organisation|null $organisation
 * @property-read \App\Models\Project|null $project
 * @property-read \App\Models\User|null $userCreated
 * @method static \Illuminate\Database\Eloquent\Builder|LeadStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeadStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeadStatus onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|LeadStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|LeadStatus whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadStatus whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadStatus whereIsClient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadStatus whereIsCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadStatus whereOrganisationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadStatus whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadStatus whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadStatus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadStatus withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|LeadStatus withoutTrashed()
 */
	class LeadStatus extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $remarks
 * @property int $user_id
 * @property string|null $meeting_from
 * @property string|null $meeting_to
 * @property string|null $started_at
 * @property int $not_held
 * @property string|null $finished_at
 * @property string|null $finished_note
 * @property int|null $project_id
 * @property int $organisation_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Organisation|null $organisation
 * @property-read \App\Models\Project|null $project
 * @property \Illuminate\Database\Eloquent\Collection<int, \Spatie\Tags\Tag> $tags
 * @property-read int|null $tags_count
 * @property-read \App\Models\User|null $userCreated
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $userParticipants
 * @property-read int|null $user_participants_count
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting query()
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereFinishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereFinishedNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereMeetingFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereMeetingTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereNotHeld($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereOrganisationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting withAllTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting withAnyTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting withoutTrashed()
 */
	class Meeting extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string $related_type
 * @property int $related_id
 * @property int $important
 * @property int $user_id
 * @property int $organisation_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Organisation|null $organisation
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $project
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $related
 * @property-read \App\Models\User|null $user
 * @property-read \App\Models\User|null $userCreated
 * @method static \Illuminate\Database\Eloquent\Builder|Note newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Note newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Note onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Note query()
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereImportant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereOrganisationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereRelatedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereRelatedType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Note withoutTrashed()
 */
	class Note extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Client> $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lead> $leads
 * @property-read int|null $leads_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Project> $projects
 * @property-read int|null $projects_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Task> $tasks
 * @property-read int|null $tasks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation withoutTrashed()
 */
	class Organisation extends \Eloquent implements \Filament\Models\Contracts\HasCurrentTenantLabel {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property int $internal
 * @property int|null $client_id
 * @property int|null $leader_id
 * @property string|null $deadline_at
 * @property int $organisation_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Client|null $client
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Client> $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Document> $documents
 * @property-read int|null $documents_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KnowledgeArticle> $knowledgeArticles
 * @property-read int|null $knowledge_articles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KnowledgeCategory> $knowledgeCategories
 * @property-read int|null $knowledge_categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LeadSource> $leadSources
 * @property-read int|null $lead_sources_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LeadStatus> $leadStatuses
 * @property-read int|null $lead_statuses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lead> $leads
 * @property-read int|null $leads_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Meeting> $meetings
 * @property-read int|null $meetings_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Note> $notes
 * @property-read int|null $notes_count
 * @property-read \App\Models\Organisation|null $organisation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProjectMilestone> $projectMilestones
 * @property-read int|null $project_milestones_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Task> $tasks
 * @property-read int|null $tasks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Vault> $vaults
 * @property-read int|null $vaults_count
 * @method static \Illuminate\Database\Eloquent\Builder|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDeadlineAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereInternal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereLeaderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereOrganisationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Project withoutTrashed()
 */
	class Project extends \Eloquent implements \Filament\Models\Contracts\HasCurrentTenantLabel {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property int $user_id
 * @property string|null $description
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $due_date
 * @property int $project_id
 * @property int $organisation_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Organisation|null $organisation
 * @property-read \App\Models\Project|null $project
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Task> $tasks
 * @property-read int|null $tasks_count
 * @property-read \App\Models\User|null $userCreated
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMilestone current()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMilestone future()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMilestone newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMilestone newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMilestone onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMilestone past()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMilestone query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMilestone whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMilestone whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMilestone whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMilestone whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMilestone whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMilestone whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMilestone whereOrganisationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMilestone whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMilestone whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMilestone whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMilestone whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMilestone withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMilestone withoutTrashed()
 */
	class ProjectMilestone extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $user_id
 * @property string|null $related_type
 * @property int|null $related_id
 * @property int|null $project_id
 * @property int|null $project_milestone_id
 * @property int $priority_id
 * @property int $status_id
 * @property string|null $completed_at
 * @property string|null $start_at
 * @property string|null $deadline_at
 * @property int $organisation_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \RyanChandler\Comments\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\User|null $creator
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $members
 * @property-read int|null $members_count
 * @property-read \App\Models\Organisation|null $organisation
 * @property-read \App\Models\Project|null $project
 * @property-read \App\Models\ProjectMilestone|null $projectMilestone
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $related
 * @property \Illuminate\Database\Eloquent\Collection<int, \Spatie\Tags\Tag> $tags
 * @property-read int|null $tags_count
 * @property-read \App\Models\User|null $userCreated
 * @method static \Illuminate\Database\Eloquent\Builder|Task newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task notCompleted()
 * @method static \Illuminate\Database\Eloquent\Builder|Task onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Task query()
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereDeadlineAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereOrganisationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task wherePriorityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereProjectMilestoneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereRelatedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereRelatedType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Task withAllTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder|Task withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Task withAnyTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder|Task withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Task withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Task withoutTrashed()
 */
	class Task extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property int $administrator
 * @property mixed $password
 * @property int $active
 * @property int $organisation_id
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $custom_fields
 * @property string|null $avatar_url
 * @property string|null $mobile
 * @property string|null $address
 * @property string|null $city
 * @property string|null $email_signature
 * @property string|null $postal_code
 * @property-read mixed $avatar
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @property-read string $full_name
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Organisation|null $organisation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Project> $projects
 * @property-read int|null $projects_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAdministrator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatarUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCustomFields($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailSignature($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOrganisationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutTrashed()
 */
	class User extends \Eloquent implements \Filament\Models\Contracts\FilamentUser, \Filament\Models\Contracts\HasDefaultTenant, \Filament\Models\Contracts\HasTenants, \Filament\Models\Contracts\HasAvatar {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $content
 * @property string $related_type
 * @property int $related_id
 * @property int $user_id
 * @property int $organisation_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Organisation|null $organisation
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $related
 * @property-read \App\Models\User|null $userCreated
 * @method static \Illuminate\Database\Eloquent\Builder|Vault newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vault newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vault onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Vault query()
 * @method static \Illuminate\Database\Eloquent\Builder|Vault whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vault whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vault whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vault whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vault whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vault whereOrganisationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vault whereRelatedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vault whereRelatedType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vault whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vault whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vault withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Vault withoutTrashed()
 */
	class Vault extends \Eloquent {}
}

