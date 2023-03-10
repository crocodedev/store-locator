import {
    Page,
    FormLayout,
    TextField,
    Card,
    Layout,
    TextContainer,
    Collapsible,
    Button,
    Banner,
} from '@shopify/polaris'
import React, {useCallback, useState} from 'react';
import '../assets/pageFAQ.css';
import {useAuthenticatedFetch} from "../hooks";

export default function FAQ() {
    const fetch = useAuthenticatedFetch();
    const rawMarkup = (text) => {
        return {__html: text};
    }

    const [FAQs, setFAQs] = useState([
        {
            status: false,
            question: "Question #1?",
            answer: "<p>Answer at question #1</p>"
        },
        {
            status: false,
            question: "Question #2?",
            answer: "<p>Answer at question #2</p>"
        },
        {
            status: false,
            question: "Question #3?",
            answer: "<p>Answer at question #3</p>"
        },
    ]);
    const handleChangeFAQ = (key) => {
        setFAQs(FAQs.map((FAQs, FAQkey) => {
            if (key === FAQkey) FAQs.status = !FAQs.status;

            return FAQs;
        }))
    }

    const [successForm, setSuccessForm] = useState(false);
    const [contactForm, setContactForm] = useState({
        email: {
            value: '',
            error: '',
            disabled: false
        },
        name: {
            value: '',
            error: '',
            disabled: false
        },
        message: {
            value: '',
            error: '',
            disabled: false
        },
    });
    const handleChangeContactForm = (newValue, id) => {
        let object = {}
        object[document.getElementById(id).name] = {}
        object[document.getElementById(id).name]['value'] = newValue;
        object[document.getElementById(id).name]['error'] = '';

        setContactForm({...contactForm, ...object});
    };
    const handleChangeContactFormErrors = (errors) => {

    };
    const sendContactForm = async () => {
        let newContactForm = contactForm;
        for (const contactFormKey in contactForm) {
            let object = {}
            object[contactFormKey] = {...newContactForm[contactFormKey], ...{disabled: true}};
            newContactForm = {...newContactForm, ...object};
            setContactForm(newContactForm);
        }

        let data = new FormData();
        for (const contactFormKey in contactForm) {
            data.append(contactFormKey, contactForm[contactFormKey].value);
        }

        const response = await fetch("/api/contact", {
            method: 'POST',
            body: data
        });

        const result = await response.json();

        for (const contactFormKey in contactForm) {
            let object = {}
            object[contactFormKey] = {...newContactForm[contactFormKey], ...{disabled: false}};
            newContactForm = {...newContactForm, ...object};
            setContactForm(newContactForm);
        }

        if (result.errors) {
            for (const errorKey in result.errors) {
                let object = {}
                object[errorKey] = {...newContactForm[errorKey], ...{error: result.errors[errorKey][0]}};
                newContactForm = {...newContactForm, ...object};
                setContactForm(newContactForm);
            }

            return false;
        }

        setContactForm({
            email: {
                value: '',
                error: '',
                disabled: false
            },
            name: {
                value: '',
                error: '',
                disabled: false
            },
            message: {
                value: '',
                error: '',
                disabled: false
            },
        });
        setSuccessForm(true);
    }

    return (
        <Page
            breadcrumbs={[{content: 'Home', url: '/'}]}
            title='FAQ & Contacts'
            divider
            fullWidth>

            <Layout>
                <Layout.Section oneThird>
                    <div style={{marginTop: 'var(--p-space-5)'}}>
                        <TextContainer>
                            <h3 style={{
                                fontSize: '16px',
                                fontWeight: '600',
                            }}>FAQ</h3>
                            <p>
                                Especially for you, we have prepared answers to frequently asked questions. We hope
                                that by finding out the answers you will feel more comfortable using our application.
                            </p>
                        </TextContainer>
                    </div>
                </Layout.Section>
                <Layout.Section>
                    <Card sectioned>
                        {FAQs.length && FAQs.map((FAQ, key) => (
                            <div onClick={() => handleChangeFAQ(key)} className="customFAQItem" key={key}>
                                <Card.Section vertical title={FAQ.question}>
                                    <Collapsible
                                        open={FAQ.status}
                                        transition={{duration: '500ms', timingFunction: 'ease-in-out'}}
                                        expandOnPrint
                                    >
                                        <TextContainer>
                                            <div dangerouslySetInnerHTML={rawMarkup(FAQ.answer)}/>
                                        </TextContainer>
                                    </Collapsible>
                                </Card.Section>
                            </div>
                        ))}
                    </Card>
                </Layout.Section>
            </Layout>

            <div style={{padding: '10px'}}/>

            <Layout>
                <Layout.Section oneThird>
                    <div style={{marginTop: 'var(--p-space-5)'}}>
                        <TextContainer>
                            <h3 style={{
                                fontSize: '16px',
                                fontWeight: '600',
                            }}>Contacts</h3>
                            <p>If you haven't found the answer to your question above or have something to suggest,
                                contact our team.</p>
                        </TextContainer>
                    </div>
                </Layout.Section>
                <Layout.Section>
                    <Card sectioned primaryFooterAction={{
                        content: 'Send',
                        onAction: () => sendContactForm(),
                        loading: (contactForm['email'].disabled || contactForm['name'].disabled || contactForm['message'].disabled)
                    }}>

                        {successForm && (
                            <div style={{
                                paddingBottom: '1rem'
                            }}>
                                <Banner
                                    title="Your application has been successfully sent!"
                                    status="success"
                                >
                                    <p> We will get back to you as soon as possible.</p>
                                </Banner>
                            </div>
                        )}

                        <FormLayout>
                            <TextField
                                label="Email"
                                type="email"
                                value={contactForm['email'].value}
                                onChange={(newValue, id) => handleChangeContactForm(newValue, id)}
                                autoComplete="email"
                                name="email"
                                error={contactForm['email'].error}
                                disabled={contactForm['email'].disabled}
                            />
                            <TextField
                                label="Full name"
                                value={contactForm['name'].value}
                                onChange={(newValue, id) => handleChangeContactForm(newValue, id)}
                                autoComplete="off"
                                name="name"
                                error={contactForm['name'].error}
                                disabled={contactForm['email'].disabled}
                            />
                            <TextField
                                label="Message"
                                value={contactForm['message'].value}
                                onChange={(newValue, id) => handleChangeContactForm(newValue, id)}
                                multiline={4}
                                autoComplete="off"
                                name="message"
                                error={contactForm['message'].error}
                                disabled={contactForm['email'].disabled}
                            />
                        </FormLayout>
                    </Card>
                </Layout.Section>
            </Layout>


        </Page>
    );
}
