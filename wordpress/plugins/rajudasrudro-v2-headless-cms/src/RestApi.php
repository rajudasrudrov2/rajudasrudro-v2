<?php
namespace RDR\V2\HeadlessCMS;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class RestApi {
    /** @var Media */
    private $media;
    /** @var Settings */
    private $settings;

    public function __construct( Media $media, Settings $settings ) {
        $this->media = $media;
        $this->settings = $settings;
    }

    public function hooks() {
        add_action( 'rest_api_init', array( $this, 'register_routes' ) );
    }

    public function register_routes() {
        register_rest_route( RDR_V2_CMS_REST_NAMESPACE, '/site', array( 'methods' => \WP_REST_Server::READABLE, 'callback' => array( $this, 'site' ), 'permission_callback' => '__return_true' ) );
        register_rest_route( RDR_V2_CMS_REST_NAMESPACE, '/services', array( 'methods' => \WP_REST_Server::READABLE, 'callback' => array( $this, 'services' ), 'permission_callback' => '__return_true' ) );
        register_rest_route( RDR_V2_CMS_REST_NAMESPACE, '/services/(?P<slug>[a-z0-9-]+)', array( 'methods' => \WP_REST_Server::READABLE, 'callback' => array( $this, 'service' ), 'permission_callback' => '__return_true' ) );
        register_rest_route( RDR_V2_CMS_REST_NAMESPACE, '/projects', array( 'methods' => \WP_REST_Server::READABLE, 'callback' => array( $this, 'projects' ), 'permission_callback' => '__return_true' ) );
        register_rest_route( RDR_V2_CMS_REST_NAMESPACE, '/projects/(?P<slug>[a-z0-9-]+)', array( 'methods' => \WP_REST_Server::READABLE, 'callback' => array( $this, 'project' ), 'permission_callback' => '__return_true' ) );
        register_rest_route( RDR_V2_CMS_REST_NAMESPACE, '/reviews', array( 'methods' => \WP_REST_Server::READABLE, 'callback' => array( $this, 'reviews' ), 'permission_callback' => '__return_true' ) );
        register_rest_route( RDR_V2_CMS_REST_NAMESPACE, '/articles', array( 'methods' => \WP_REST_Server::READABLE, 'callback' => array( $this, 'articles' ), 'permission_callback' => '__return_true' ) );
        register_rest_route( RDR_V2_CMS_REST_NAMESPACE, '/articles/(?P<slug>[a-z0-9-]+)', array( 'methods' => \WP_REST_Server::READABLE, 'callback' => array( $this, 'article' ), 'permission_callback' => '__return_true' ) );
        register_rest_route( RDR_V2_CMS_REST_NAMESPACE, '/about', array( 'methods' => \WP_REST_Server::READABLE, 'callback' => array( $this, 'about' ), 'permission_callback' => '__return_true' ) );
    }

    public function site() {
        $site = $this->settings->site();
        return rest_ensure_response( array(
            'type'                => 'site',
            'publicEmail'         => isset( $site['public_email'] ) ? (string) $site['public_email'] : '',
            'fiverrUrl'           => isset( $site['fiverr_url'] ) ? (string) $site['fiverr_url'] : '',
            'responseExpectation' => isset( $site['response_expectation'] ) ? (string) $site['response_expectation'] : '',
            'social'              => array( 'linkedin' => isset( $site['linkedin_url'] ) ? (string) $site['linkedin_url'] : '' ),
            'defaultSocialImage'  => $this->media->normalize( isset( $site['default_social_image_id'] ) ? $site['default_social_image_id'] : 0 ),
        ) );
    }

    public function services() {
        $posts = $this->public_query( PostTypes::SERVICE );
        return rest_ensure_response( array_values( array_map( array( $this, 'service_list_dto' ), $posts ) ) );
    }

    public function service( \WP_REST_Request $request ) {
        $post = $this->find_public_by_slug( PostTypes::SERVICE, $request['slug'] );
        return $post ? rest_ensure_response( $this->service_detail_dto( $post ) ) : $this->not_found( 'service' );
    }

    public function projects() {
        $posts = $this->public_query( PostTypes::PROJECT );
        return rest_ensure_response( array_values( array_map( array( $this, 'project_list_dto' ), $posts ) ) );
    }

    public function project( \WP_REST_Request $request ) {
        $post = $this->find_public_by_slug( PostTypes::PROJECT, $request['slug'] );
        return $post ? rest_ensure_response( $this->project_detail_dto( $post ) ) : $this->not_found( 'project' );
    }

    public function reviews() {
        $posts = $this->public_query( PostTypes::REVIEW );
        return rest_ensure_response( array_values( array_map( array( $this, 'review_dto' ), $posts ) ) );
    }

    public function articles() {
        $posts = $this->public_query( 'post' );
        return rest_ensure_response( array_values( array_map( array( $this, 'article_list_dto' ), $posts ) ) );
    }

    public function article( \WP_REST_Request $request ) {
        $post = $this->find_public_by_slug( 'post', $request['slug'] );
        return $post ? rest_ensure_response( $this->article_detail_dto( $post ) ) : $this->not_found( 'article' );
    }

    public function about() {
        $about = $this->settings->about();
        return rest_ensure_response( array(
            'type'             => 'about',
            'intro'            => isset( $about['intro'] ) ? (string) $about['intro'] : '',
            'storyHtml'        => isset( $about['story'] ) ? wp_kses_post( $about['story'] ) : '',
            'pillars'          => isset( $about['pillars'] ) && is_array( $about['pillars'] ) ? array_values( $about['pillars'] ) : array(),
            'principles'       => isset( $about['principles'] ) && is_array( $about['principles'] ) ? array_values( $about['principles'] ) : array(),
            'expectations'     => isset( $about['expectations'] ) && is_array( $about['expectations'] ) ? array_values( $about['expectations'] ) : array(),
            'personalNote'     => isset( $about['personal_note'] ) ? (string) $about['personal_note'] : '',
            'portrait'         => $this->media->normalize( isset( $about['portrait_media_id'] ) ? $about['portrait_media_id'] : 0 ),
            'featuredProjects' => $this->relation_list( isset( $about['featured_project_ids'] ) ? $about['featured_project_ids'] : array(), PostTypes::PROJECT ),
            'featuredServices' => $this->relation_list( isset( $about['featured_service_ids'] ) ? $about['featured_service_ids'] : array(), PostTypes::SERVICE ),
            'featuredReviews'  => $this->review_relation_list( isset( $about['featured_review_ids'] ) ? $about['featured_review_ids'] : array() ),
        ) );
    }

    private function public_query( $post_type ) {
        $posts = get_posts( array(
            'post_type'      => $post_type,
            'post_status'    => 'publish',
            'posts_per_page' => 100,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'meta_query'     => array(
                array( 'key' => Meta::PUBLICATION_STATE, 'value' => 'public', 'compare' => '=' ),
            ),
            'no_found_rows'  => true,
        ) );
        usort( $posts, static function( $a, $b ) {
            $a_order = (int) get_post_meta( $a->ID, '_rdr_display_order', true );
            $b_order = (int) get_post_meta( $b->ID, '_rdr_display_order', true );
            if ( $a_order === $b_order ) {
                return strcmp( $b->post_date_gmt, $a->post_date_gmt );
            }
            return $a_order <=> $b_order;
        } );
        return $posts;
    }

    private function find_public_by_slug( $post_type, $slug ) {
        $slug = sanitize_title( $slug );
        $posts = get_posts( array(
            'name'           => $slug,
            'post_type'      => $post_type,
            'post_status'    => 'publish',
            'posts_per_page' => 1,
            'meta_query'     => array(
                array( 'key' => Meta::PUBLICATION_STATE, 'value' => 'public', 'compare' => '=' ),
            ),
            'no_found_rows'  => true,
        ) );
        return ! empty( $posts ) && $posts[0] instanceof \WP_Post ? $posts[0] : null;
    }

    private function identity( \WP_Post $post, $type ) {
        return array(
            'id'   => (int) $post->ID,
            'slug' => (string) $post->post_name,
            'type' => $type,
        );
    }

    public function service_list_dto( \WP_Post $post ) {
        return array_merge( $this->identity( $post, 'service' ), array(
            'title'            => get_the_title( $post ),
            'shortDescription' => (string) get_post_meta( $post->ID, '_rdr_short_description', true ),
            'featured'         => (bool) get_post_meta( $post->ID, '_rdr_featured', true ),
            'order'            => (int) get_post_meta( $post->ID, '_rdr_display_order', true ),
            'heroMedia'        => $this->media->normalize( get_post_meta( $post->ID, '_rdr_hero_media_id', true ) ),
        ) );
    }

    private function service_detail_dto( \WP_Post $post ) {
        return array_merge( $this->service_list_dto( $post ), array(
            'positioning'     => (string) get_post_meta( $post->ID, '_rdr_positioning', true ),
            'heroHtml'        => wp_kses_post( (string) get_post_meta( $post->ID, '_rdr_hero_copy', true ) ),
            'capabilities'    => $this->array_meta( $post->ID, '_rdr_capabilities' ),
            'deliverables'    => $this->array_meta( $post->ID, '_rdr_deliverables' ),
            'useCases'        => $this->array_meta( $post->ID, '_rdr_use_cases' ),
            'formats'         => $this->array_meta( $post->ID, '_rdr_formats' ),
            'process'         => $this->rows_meta( $post->ID, '_rdr_process_steps' ),
            'faq'             => $this->rows_meta( $post->ID, '_rdr_faq' ),
            'whyRajuHtml'     => wp_kses_post( (string) get_post_meta( $post->ID, '_rdr_why_raju', true ) ),
            'seo'             => $this->seo_dto( $post->ID ),
        ) );
    }

    public function project_list_dto( \WP_Post $post ) {
        return array_merge( $this->identity( $post, 'project' ), array(
            'title'          => get_the_title( $post ),
            'shortSummary'   => (string) get_post_meta( $post->ID, '_rdr_short_summary', true ),
            'featureMedia'   => $this->media->normalize( get_post_meta( $post->ID, '_rdr_feature_media_id', true ) ?: get_post_thumbnail_id( $post->ID ) ),
            'categories'     => $this->term_dtos( $post->ID, Taxonomies::PROJECT_CATEGORY ),
            'relatedService' => $this->relation( get_post_meta( $post->ID, '_rdr_related_service_id', true ), PostTypes::SERVICE ),
            'featured'       => (bool) get_post_meta( $post->ID, '_rdr_featured', true ),
            'caseStudy'      => $this->case_study_summary( $post ),
            'order'          => (int) get_post_meta( $post->ID, '_rdr_display_order', true ),
        ) );
    }

    private function project_detail_dto( \WP_Post $post ) {
        return array_merge( $this->project_list_dto( $post ), array(
            'overviewHtml' => wp_kses_post( apply_filters( 'the_content', $post->post_content ) ),
            'gallery'      => $this->media->normalize_many( get_post_meta( $post->ID, '_rdr_gallery_media_ids', true ) ),
            'caseStudy'    => $this->case_study_detail( $post ),
            'seo'          => $this->seo_dto( $post->ID ),
        ) );
    }

    public function review_dto( \WP_Post $post ) {
        $rating = (float) get_post_meta( $post->ID, '_rdr_rating', true );
        $date = (string) get_post_meta( $post->ID, '_rdr_review_date', true );
        return array_merge( $this->identity( $post, 'review' ), array(
            'reviewer'       => (string) get_post_meta( $post->ID, '_rdr_reviewer_name', true ),
            'text'           => (string) get_post_meta( $post->ID, '_rdr_review_text', true ),
            'rating'         => $rating >= 1 && $rating <= 5 ? $rating : null,
            'source'         => (string) get_post_meta( $post->ID, '_rdr_source', true ),
            'sourceUrl'      => (string) get_post_meta( $post->ID, '_rdr_source_url', true ),
            'context'        => (string) get_post_meta( $post->ID, '_rdr_context', true ),
            'country'        => (string) get_post_meta( $post->ID, '_rdr_country', true ),
            'reviewDate'     => '' !== $date ? $date : null,
            'featured'       => (bool) get_post_meta( $post->ID, '_rdr_featured', true ),
            'relatedService' => $this->relation( get_post_meta( $post->ID, '_rdr_related_service_id', true ), PostTypes::SERVICE ),
            'relatedProject' => $this->relation( get_post_meta( $post->ID, '_rdr_related_project_id', true ), PostTypes::PROJECT ),
            'order'          => (int) get_post_meta( $post->ID, '_rdr_display_order', true ),
        ) );
    }

    public function article_list_dto( \WP_Post $post ) {
        return array_merge( $this->identity( $post, 'article' ), array(
            'title'         => get_the_title( $post ),
            'excerpt'       => has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_trim_words( wp_strip_all_tags( $post->post_content ), 32 ),
            'categories'    => $this->term_dtos( $post->ID, 'category' ),
            'featureImage'  => $this->media->normalize( get_post_thumbnail_id( $post->ID ) ),
            'featured'      => (bool) get_post_meta( $post->ID, '_rdr_featured', true ),
            'publishedAt'   => get_post_time( DATE_ATOM, true, $post ),
            'updatedAt'     => get_post_modified_time( DATE_ATOM, true, $post ),
            'author'        => $this->author_dto( $post->post_author ),
            'readingTime'   => $this->reading_time( $post->post_content ),
        ) );
    }

    private function article_detail_dto( \WP_Post $post ) {
        return array_merge( $this->article_list_dto( $post ), array(
            'bodyHtml'        => wp_kses_post( apply_filters( 'the_content', $post->post_content ) ),
            'relatedServices' => $this->relation_list( get_post_meta( $post->ID, '_rdr_related_service_ids', true ), PostTypes::SERVICE ),
            'relatedProjects' => $this->relation_list( get_post_meta( $post->ID, '_rdr_related_project_ids', true ), PostTypes::PROJECT ),
            'seo'             => $this->seo_dto( $post->ID ),
        ) );
    }

    private function case_study_summary( \WP_Post $post ) {
        $enabled = (bool) get_post_meta( $post->ID, '_rdr_case_study_enabled', true );
        $status = (string) get_post_meta( $post->ID, '_rdr_case_status', true );
        return array( 'available' => $enabled && 'available' === $status );
    }

    private function case_study_detail( \WP_Post $post ) {
        $summary = $this->case_study_summary( $post );
        if ( empty( $summary['available'] ) ) {
            return null;
        }
        return array(
            'overviewHtml'      => wp_kses_post( (string) get_post_meta( $post->ID, '_rdr_case_overview', true ) ),
            'challengeHtml'     => wp_kses_post( (string) get_post_meta( $post->ID, '_rdr_case_challenge', true ) ),
            'approachHtml'      => wp_kses_post( (string) get_post_meta( $post->ID, '_rdr_case_approach', true ) ),
            'deliverables'      => $this->array_meta( $post->ID, '_rdr_case_deliverables' ),
            'creativeHtml'      => wp_kses_post( (string) get_post_meta( $post->ID, '_rdr_case_creative', true ) ),
            'outcomesHtml'      => wp_kses_post( (string) get_post_meta( $post->ID, '_rdr_case_outcomes', true ) ),
            'relatedReview'     => $this->relation( get_post_meta( $post->ID, '_rdr_related_review_id', true ), PostTypes::REVIEW ),
            'relatedProjects'   => $this->relation_list( get_post_meta( $post->ID, '_rdr_related_project_ids', true ), PostTypes::PROJECT ),
        );
    }

    private function relation( $id, $post_type ) {
        $id = absint( $id );
        if ( ! $id ) {
            return null;
        }
        $post = get_post( $id );
        if ( ! $post instanceof \WP_Post || $post_type !== $post->post_type || 'publish' !== $post->post_status || 'public' !== get_post_meta( $post->ID, Meta::PUBLICATION_STATE, true ) ) {
            return null;
        }
        return array( 'id' => (int) $post->ID, 'slug' => (string) $post->post_name, 'title' => get_the_title( $post ) );
    }

    private function relation_list( $ids, $post_type ) {
        $items = array();
        foreach ( Meta::sanitize_id_array( $ids ) as $id ) {
            $item = $this->relation( $id, $post_type );
            if ( null !== $item ) {
                $items[] = $item;
            }
        }
        return $items;
    }

    private function review_relation_list( $ids ) {
        $items = array();
        foreach ( Meta::sanitize_id_array( $ids ) as $id ) {
            $post = get_post( $id );
            if ( ! $post instanceof \WP_Post || PostTypes::REVIEW !== $post->post_type || 'publish' !== $post->post_status || 'public' !== get_post_meta( $post->ID, Meta::PUBLICATION_STATE, true ) ) {
                continue;
            }
            $items[] = array( 'id' => (int) $post->ID, 'slug' => (string) $post->post_name, 'reviewer' => (string) get_post_meta( $post->ID, '_rdr_reviewer_name', true ) );
        }
        return $items;
    }

    private function term_dtos( $post_id, $taxonomy ) {
        $terms = get_the_terms( $post_id, $taxonomy );
        if ( ! is_array( $terms ) ) {
            return array();
        }
        return array_values( array_map( static function( $term ) {
            return array( 'id' => (int) $term->term_id, 'slug' => (string) $term->slug, 'name' => (string) $term->name );
        }, $terms ) );
    }

    private function seo_dto( $post_id ) {
        return array(
            'title'       => (string) get_post_meta( $post_id, '_rdr_seo_title', true ),
            'description' => (string) get_post_meta( $post_id, '_rdr_meta_description', true ),
            'ogImage'     => $this->media->normalize( get_post_meta( $post_id, '_rdr_og_image_id', true ) ),
        );
    }

    private function array_meta( $post_id, $key ) {
        $value = get_post_meta( $post_id, $key, true );
        return is_array( $value ) ? array_values( $value ) : array();
    }

    private function rows_meta( $post_id, $key ) {
        $rows = $this->array_meta( $post_id, $key );
        $clean = array();
        foreach ( $rows as $row ) {
            if ( is_array( $row ) ) {
                $clean[] = array( 'title' => isset( $row['title'] ) ? (string) $row['title'] : '', 'body' => isset( $row['body'] ) ? (string) $row['body'] : '' );
            }
        }
        return $clean;
    }

    private function author_dto( $user_id ) {
        $user = get_userdata( absint( $user_id ) );
        if ( ! $user ) {
            return null;
        }
        return array( 'id' => (int) $user->ID, 'name' => (string) $user->display_name );
    }

    private function reading_time( $html ) {
        $words = str_word_count( wp_strip_all_tags( (string) $html ) );
        return max( 1, (int) ceil( $words / 220 ) );
    }

    private function not_found( $type ) {
        return new \WP_Error( 'rdr_not_found', ucfirst( $type ) . ' not found.', array( 'status' => 404 ) );
    }
}
