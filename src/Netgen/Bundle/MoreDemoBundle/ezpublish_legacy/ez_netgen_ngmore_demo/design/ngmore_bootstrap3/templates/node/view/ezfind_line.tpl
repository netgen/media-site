{symfony_render(
    symfony_controller(
        'ez_content:viewLocation',
        hash(
            'locationId', $node.node_id,
            'viewType', 'search',
            'params', hash(
                'score_percent', $node.score_percent
            )
        )
    )
)}
