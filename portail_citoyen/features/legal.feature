Feature:
    In order to show the legal page
    As a user
    I need to be able to see the legal page

    @func
    Scenario: I am on the legal page, I can see the legal content
        Given I am on "/infos/mentions-legales"
        Then the response status code should be 200
        And I should see "Mentions légales - Plainte en ligne" in the "title" element
        And I should see the key "pel.legal.website.address.text" translated
        And I should see the key "pel.legal.website.address" translated
        And I should see the key "pel.legal.editors.information" translated
        And I should see the key "pel.legal.editors.information.foreword" translated
        And I should see the key "pel.legal.contact" translated
        And I should see the key "pel.legal.dggn.address.label" translated
        And I should see the key "pel.legal.dggn" translated
        And I should see the key "pel.legal.dggn.address.street" translated
        And I should see the key "pel.legal.dggn.address.city" translated
        And I should see the key "pel.legal.dgpn.address.label" translated
        And I should see the key "pel.legal.ministry.of.the.interior" translated
        And I should see the key "pel.legal.dgpn" translated
        And I should see the key "pel.legal.dgpn.address.street" translated
        And I should see the key "pel.legal.dgpn.address.city" translated
        And I should see the key "pel.legal.dgpn.director" translated
        And I should see the key "pel.legal.dggn.director" translated
        And I should see the key "pel.legal.publication.directors" translated
        And I should see the key "pel.legal.editorial.and.technical.team" translated
        And I should see the key "pel.legal.anfsi" translated
        And I should see the key "pel.legal.dpai.dpru" translated
        And I should see the key "pel.legal.editorial.and.technical.team.address.street" translated
        And I should see the key "pel.legal.editorial.and.technical.team.address.city" translated
        And I should see the key "pel.legal.hosting.information" translated
        And I should see the key "pel.legal.hosting.text" translated
        And I should see the key "pel.legal.host.name" translated
        And I should see the key "pel.legal.host.address.street" translated
        And I should see the key "pel.legal.host.address.city" translated
        And I should see the key "pel.legal.host.siret" translated
        And I should see the key "pel.legal.host.phone" translated
        And I should see the key "pel.legal.copyright" translated
        And I should see the key "pel.legal.online.content.reuse" translated
        And I should see the key "pel.legal.online.content.reuse.text.1" translated
        And I should see the key "pel.legal.online.content.reuse.under.etalab" translated
        And I should see the key "pel.legal.online.content.reuse.text.2" translated
        And I should see the key "pel.legal.link.creation" translated
        And I should see the key "pel.legal.link.creation.text.1" translated
        And I should see the key "pel.legal.link.creation.text.2" translated
        And I should see the key "pel.legal.graphic.creations.photo.credits.videos" translated
        And I should see the key "pel.legal.graphic.creations.photo.credits.videos.text" translated
        And I should see the key "pel.legal.graphic.creations.photo.credits.videos.ask.to" translated
        And I should see the key "pel.legal.graphic.creations.photo.credits.videos.ask.by.email" translated
        And I should see the key "pel.legal.graphic.creations.photo.credits.videos.ask.by.mail" translated
        And I should see the key "pel.legal.graphic.creations.photo.credits.videos.name" translated
        And I should see the key "pel.legal.picture.video" translated
        And I should see the key "pel.legal.graphic.creations.photo.credits.videos.address.street" translated
        And I should see the key "pel.legal.graphic.creations.photo.credits.videos.address.city" translated
        And I should see the key "pel.legal.intellectual.property" translated
        And I should see the key "pel.legal.homepage.pictures" translated
        And I should see the key "pel.legal.trademark" translated
        And I should see the key "pel.legal.website.content" translated
        And I should see the key "pel.legal.warning" translated
        And I should see the key "pel.legal.warning.text.1" translated
        And I should see the key "pel.legal.warning.text.2" translated
        And I should see the key "pel.legal.links" translated
        And I should see the key "pel.legal.links.text.1" translated
        And I should see the key "pel.legal.links.text.2" translated
        And I should see the key "pel.legal.website.access" translated
        And I should see the key "pel.legal.website.access.text.1" translated
        And I should see the key "pel.legal.website.access.text.2" translated
