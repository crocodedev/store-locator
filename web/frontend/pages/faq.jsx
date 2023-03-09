import {
    Page,
    FormLayout,
    TextField,
    Card,
    Layout,
    TextContainer,
    Collapsible,
    Button,
} from '@shopify/polaris'
import React, {useCallback, useState} from 'react';
import '../assets/pageFAQ.css';

export default function FAQ() {
    const rawMarkup = (text) => {
        return { __html: text };
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

    const [contactForm, setContactForm] = useState({
        email: '',
        name: '',
        message: '',
    });
    const handleChangeContactForm = (newValue, id) => {
        let object = {}
        object[document.getElementById(id).name] = newValue;

        setContactForm({...contactForm, ...object});
    };

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
                            <div onClick={() => handleChangeFAQ(key)} className="customFAQItem">
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
                        onAction: () => console.log('Send contact form')
                    }}>
                        <FormLayout>
                            <TextField
                                label="Email"
                                type="email"
                                value={contactForm.email}
                                onChange={(newValue, id) => handleChangeContactForm(newValue, id)}
                                autoComplete="email"
                                name="email"
                            />
                            <TextField
                                label="Full name"
                                value={contactForm.name}
                                onChange={(newValue, id) => handleChangeContactForm(newValue, id)}
                                autoComplete="off"
                                name="name"
                            />
                            <TextField
                                label="Message"
                                value={contactForm.message}
                                onChange={(newValue, id) => handleChangeContactForm(newValue, id)}
                                multiline={4}
                                autoComplete="off"
                                name="message"
                            />
                        </FormLayout>
                    </Card>
                </Layout.Section>
            </Layout>


        </Page>
    );
}
