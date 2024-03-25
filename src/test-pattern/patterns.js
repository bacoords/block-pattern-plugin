export default {
	pattern1: {
		template: [
			[
				"core/group",
				{
					layout: {
						type: "constrained",
					},
				},
				[
					[
						"core/heading",
						{
							textAlign: "center",
						},
						[],
					],
					[
						"core/paragraph",
						{
							align: "center",
						},
						[],
					],
					[
						"core/columns",
						{
							align: "wide",
						},
						[
							[
								"core/column",
								{},
								[
									[
										"core/image",
										{
											aspectRatio: "3/4",
											scale: "cover",
										},
										[],
									],
									[
										"core/heading",
										{
											textAlign: "center",
										},
										[],
									],
									[
										"core/paragraph",
										{
											align: "center",
										},
										[],
									],
									[
										"core/buttons",
										{
											layout: {
												type: "flex",
												justifyContent: "center",
											},
										},
										[["core/button", {}, []]],
									],
								],
							],
							[
								"core/column",
								{},
								[
									[
										"core/image",
										{
											aspectRatio: "3/4",
											scale: "cover",
										},
										[],
									],
									[
										"core/heading",
										{
											textAlign: "center",
										},
										[],
									],
									[
										"core/paragraph",
										{
											align: "center",
										},
										[],
									],
									[
										"core/buttons",
										{
											layout: {
												type: "flex",
												justifyContent: "center",
											},
										},
										[["core/button", {}, []]],
									],
								],
							],
							[
								"core/column",
								{},
								[
									[
										"core/image",
										{
											aspectRatio: "3/4",
											scale: "cover",
										},
										[],
									],
									[
										"core/heading",
										{
											textAlign: "center",
										},
										[],
									],
									[
										"core/paragraph",
										{
											align: "center",
										},
										[],
									],
									[
										"core/buttons",
										{
											layout: {
												type: "flex",
												justifyContent: "center",
											},
										},
										[["core/button", {}, []]],
									],
								],
							],
						],
					],
				],
			],
		],
	},
	pattern2: {
		template: [
			[
				"core/group",
				{
					layout: {
						type: "constrained",
					},
				},
				[
					[
						"core/columns",
						{
							verticalAlignment: null,
							align: "wide",
						},
						[
							[
								"core/column",
								{},
								[
									[
										"core/image",
										{
											aspectRatio: "4/3",
											scale: "cover",
											className: "is-style-rounded",
										},
										[],
									],
								],
							],
							[
								"core/column",
								{
									verticalAlignment: "center",
								},
								[
									[
										"core/group",
										{
											layout: {
												type: "flex",
												orientation: "vertical",
											},
										},
										[
											["core/heading", {}, []],
											["core/paragraph", {}, []],
											["core/buttons", {}, [["core/button", {}, []]]],
										],
									],
								],
							],
						],
					],
				],
			],
		],
	},
	pattern3: {
		template: [
			[
				"core/group",
				{
					layout: {
						type: "constrained",
					},
				},
				[
					[
						"core/columns",
						{
							align: "wide",
						},
						[
							[
								"core/column",
								{},
								[
									[
										"core/image",
										{
											aspectRatio: "4/3",
											scale: "cover",
											className: "is-style-rounded",
										},
										[],
									],
								],
							],
							[
								"core/column",
								{
									verticalAlignment: "center",
								},
								[
									[
										"core/group",
										{
											layout: {
												type: "flex",
												orientation: "vertical",
											},
										},
										[
											["core/heading", {}, []],
											["core/paragraph", {}, []],
											["core/separator", {}, []],
											["core/list", {}, [["core/list-item", {}, []]]],
										],
									],
								],
							],
						],
					],
				],
			],
		],
	},
	pattern4: {
		template: [
			[
				"core/pattern",
				{
					slug: "tangent/call-to-action",
				},
			],
		],
	},
};
